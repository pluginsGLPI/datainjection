<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

use Glpi\Api\HL\Controller\AbstractController;
use Glpi\Api\HL\Doc as Doc;
use Glpi\Api\HL\Route;
use Glpi\Api\HL\Search;
use Glpi\Http\JSONResponse;
use Glpi\Http\Request;
use Glpi\Http\Response;
use GuzzleHttp\Psr7\Utils;

#[Route(path: '/Plugin/DataInjection', requirements: [
    'id' => '\d+'
], priority: 1, tags: ['Data Injection'])]
class PluginDatainjectionApiController extends AbstractController
{
    protected static function getRawKnownSchemas(): array
    {
        return [
            'DataInjectionModel' => [
                'type' => Doc\Schema::TYPE_OBJECT,
                'x-itemtype' => PluginDatainjectionModel::class,
                'properties' => [
                    'id' => [
                        'type' => Doc\Schema::TYPE_INTEGER,
                        'format' => Doc\Schema::FORMAT_INTEGER_INT64,
                    ],
                    'name' => ['type' => Doc\Schema::TYPE_STRING],
                    'comment' => ['type' => Doc\Schema::TYPE_STRING],
                    'itemtype' => ['type' => Doc\Schema::TYPE_STRING],
                    'entity' => self::getDropdownTypeSchema(class: Entity::class, full_schema: 'Entity'),
                    'date_creation' => ['type' => Doc\Schema::TYPE_STRING, 'format' => Doc\Schema::FORMAT_STRING_DATE_TIME],
                    'date_mod' => ['type' => Doc\Schema::TYPE_STRING, 'format' => Doc\Schema::FORMAT_STRING_DATE_TIME],
                    'allow_add' => [
                        'type' => Doc\Schema::TYPE_BOOLEAN,
                        'x-field' => 'behavior_add',
                        'description' => 'Allow creation of new items'
                    ],
                    'allow_update' => [
                        'type' => Doc\Schema::TYPE_BOOLEAN,
                        'x-field' => 'behavior_update',
                        'description' => 'Allow update of existing items'
                    ],
                    'allow_add_linked_items' => [
                        'type' => Doc\Schema::TYPE_BOOLEAN,
                        'x-field' => 'can_add_dropdown',
                        'description' => 'Allow creation of dropdowns/linked items'
                    ],
                    'allow_overwrite_fields' => [
                        'type' => Doc\Schema::TYPE_BOOLEAN,
                        'x-field' => 'can_overwrite_if_not_empty',
                        'description' => 'Allow update of existing fields'
                    ],
                    'is_private' => ['type' => Doc\Schema::TYPE_BOOLEAN],
                    'user' => self::getDropdownTypeSchema(class: User::class, full_schema: 'User'),
                    'date_format' => ['type' => Doc\Schema::TYPE_STRING],
                    'float_format' => ['type' => Doc\Schema::TYPE_STRING],
                    'unique_ports' => [
                        'type' => Doc\Schema::TYPE_BOOLEAN,
                        'x-field' => 'port_unicity',
                    ],
                    'csv_options' => [
                        'type' => Doc\Schema::TYPE_OBJECT,
                        'x-join' => [
                            'table' => 'glpi_plugin_datainjection_modelcsvs',
                            'field' => 'models_id',
                            'fkey' => 'id',
                            'primary-property' => 'id',
                        ],
                        'properties' => [
                            'id' => [
                                'type' => Doc\Schema::TYPE_INTEGER,
                                'format' => Doc\Schema::FORMAT_INTEGER_INT64,
                            ],
                            'model' => self::getDropdownTypeSchema(class: PluginDatainjectionModel::class, field: 'models_id'),
                            'delimiter' => [
                                'type' => Doc\Schema::TYPE_STRING,
                            ],
                            'is_header_present' => [
                                'type' => Doc\Schema::TYPE_BOOLEAN,
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }

    #[Route(path: '/Model', methods: ['GET'])]
    #[Doc\Route(
        description: 'List or search data injection models',
        responses: [
            ['schema' => 'DataInjectionModel[]']
        ]
    )]
    public function getModels(Request $request): Response
    {
        $response = Search::searchBySchema($this->getKnownSchema('DataInjectionModel'), $request->getParameters());
        $decoded = json_decode((string) $response->getBody(), true);
        foreach ($decoded as &$model) {
            // Keep only the id and name of the model
            $model['csv_options']['model'] = [
                'id' => $model['csv_options']['model']['id'],
                'name' => $model['csv_options']['model']['name'],
            ];
        }
        unset($model);
        $response = $response->withBody(Utils::streamFor(json_encode($decoded)));
        return $response;
    }

    #[Route(path: '/Model/{id}', methods: ['GET'])]
    #[Doc\Route(
        description: 'Get a data injection model',
        responses: [
            ['schema' => 'DataInjectionModel']
        ]
    )]
    public function getModel(Request $request): Response
    {
        return Search::getOneBySchema($this->getKnownSchema('DataInjectionModel'), $request->getAttributes(), $request->getParameters());
    }

    #[Route(path: '/Model/{id}/Injection', methods: ['POST'])]
    #[Doc\Route(
        description: 'Inject data using the specified model',
        parameters: [
            [
                'name' => '_',
                'location' => Doc\Parameter::LOCATION_BODY,
                'schema' => [
                    'type' => Doc\Schema::TYPE_OBJECT,
                    'properties' => [
                        'filename' => [
                            'type' => Doc\Schema::TYPE_STRING,
                            'format' => Doc\Schema::FORMAT_STRING_BINARY
                        ]
                    ]
                ],
                'required' => false,
                'content_type' => 'multipart/form-data'
            ],
        ]
    )]
    public function inject(Request $request): Response
    {
        $model = new PluginDatainjectionModel();
        if (!$model->getFromDB($request->getAttribute('id'))) {
            return self::getNotFoundErrorResponse();
        }

        $results = [];
        foreach ($request->getUploadedFiles() as $file) {
            $options = [
                'file_encoding' => PluginDatainjectionBackend::ENCODING_AUTO,
                'from_api' => true,
                'original_filename' => $file['name'],
                'unique_filename' => $file['tmp_name'],
                'mode' => PluginDatainjectionModel::PROCESS,
                'delete_file' => true,
            ];
            $action_results = [];
            $success = $model->processUploadedFile($options);
            if (!$success) {
                return new JSONResponse(
                    self::getErrorResponseBody(AbstractController::ERROR_GENERIC, 'Error processing file'),
                    400
                );
            }
            $engine = new PluginDatainjectionEngine($model, [], $_SESSION['glpiactive_entity'] ?? 0);
            $first = true;
            foreach ($model->injectionData->getData() as $id => $data) {
                if ($first && $model->getSpecificModel()->isHeaderPresent()) {
                    $first = false;
                } else {
                    $action_results[] = $engine->injectLine($data[0], $id);
                }
            }
            $model->cleanData();
            $results[] = [
                'filename' => $file['name'],
                'results' => $action_results
            ];
        }
        return new JSONResponse($results);
    }

    #[Route(path: '/Itemtype', methods: ['GET'])]
    #[Doc\Route(
        description: 'List item types',
        responses: [
            ['schema' => 'string[]']
        ]
    )]
    public function getItemtypes(Request $request): Response
    {
        return new JSONResponse(PluginDatainjectionInjectionType::getItemtypes());
    }
}
