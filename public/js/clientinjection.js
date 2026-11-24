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

$(function() {
    document.querySelectorAll('[data-datainjection-model-form]:not([data-datainjection-model-form-ready])').forEach((span) => {
        span.setAttribute('data-datainjection-model-form-ready', '');

        const rand = span.dataset.rand;
        const uploadUrl = span.dataset.uploadUrl;
        const resultUrl = span.dataset.resultUrl;
        const modelId = Number(span.dataset.modelsId);
        const step = Number(span.dataset.step);
        const resultStep = Number(span.dataset.resultStep);

        function loadFormForModel(modelId) {
            if (modelId > 0) {
                const url = (step === resultStep) ? resultUrl : uploadUrl;
                $('#span_injection').load(url, { models_id: modelId });
            }
        }

        // On model change
        $('#clientinjection_form' + rand + ' select[name="dropdown_models"]').on('change select2:select', function() {
            loadFormForModel($(this).val());
        });

        // Initial load
        if (modelId > 0) {
            loadFormForModel(modelId);
        }
    });
});
