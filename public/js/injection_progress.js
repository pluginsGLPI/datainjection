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

/**
 * Start the batch injection process driven by the injection container element.
 *
 * @param {HTMLElement} container  The element carrying data-* config attributes.
 */
function startBatchInjection(container) {
    const batchUrl     = container.dataset.batchUrl;
    const resultUrl    = container.dataset.resultUrl;
    const modelId      = parseInt(container.dataset.modelId, 10);
    const nblines      = parseInt(container.dataset.nblines, 10);
    const batchSize    = parseInt(container.dataset.batchSize || '10', 10);
    const statusLabel  = container.dataset.statusLabel || '';
    const linesLabel   = container.dataset.linesLabel || '';
    const errorLabel   = container.dataset.errorLabel || '';

    let offset = 0;

    function updateProgressBar(progress) {
        const progressBar = container.querySelector('.progress-bar');
        const progressContainer = container.querySelector('.progress');
        if (progressBar && progressContainer) {
            progressBar.style.width = progress + '%';
            progressBar.textContent = progress + '%';
            progressContainer.setAttribute('aria-valuenow', progress);
        }
    }

    function updateStatus(processed, total) {
        const status = container.querySelector('#injection_status');
        if (status) {
            status.textContent = statusLabel + ' — ' + processed + ' / ' + total + ' ' + linesLabel;
        }
    }

    function processBatch() {
        $.ajax({
            url: batchUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                offset: offset,
                batch_size: batchSize
            },
            success: function(response) {
                updateProgressBar(response.progress);
                updateStatus(response.processed, response.total);
                offset = response.offset;

                if (response.done) {
                    const progressBar = container.querySelector('.progress-bar');
                    if (progressBar) {
                        progressBar.classList.remove('progress-bar-animated');
                    }
                    $('#span_injection').load(resultUrl, {
                        models_id: modelId,
                        nblines: nblines
                    });
                } else {
                    processBatch();
                }
            },
            error: function() {
                const progressBar = container.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.textContent = errorLabel;
                }
            }
        });
    }

    processBatch();
}
