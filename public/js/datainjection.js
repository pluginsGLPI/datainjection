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
 * Update the rows of a mappings / infos form without rebuilding them:
 * - on table change, only the options of the field dropdown are replaced,
 * - on field change, the link field switch and the conflict warning are shown or hidden in place.
 *
 * Each row must hold its parameters in a `data-field-params` attribute.
 *
 * @param {HTMLFormElement} form
 * @param {string} url   URL of the fields choices endpoint
 */
function initMappingRows(form, url) {
   if (!form) {
      return;
   }

   const updateRow = (row) => {
      const params   = JSON.parse(row.dataset.fieldParams);
      const itemtype = row.querySelector('select[name$="[itemtype]"]').value;
      const value    = row.querySelector('select[name$="[value]"]').value;
      const label    = row.querySelector('[data-mandatory-switch]');
      const input    = label.querySelector('input');

      const available = params.called_by === 'PluginDatainjectionInfo'
         || (itemtype === params.primary_type && !params.non_mandatory.includes(value));

      label.classList.toggle('invisible', !available);
      input.disabled = !available;
      if (!available) {
         input.checked = false;
      }

      // Field also targeted by the other kind of rows (mapping vs additional information)
      const conflict = params.other_targets.includes(`${itemtype}|${value}`);
      row.querySelector('[data-field-conflict]').classList.toggle('invisible', !conflict);
      row.classList.toggle('datainjection-row-conflict', conflict);
   };

   $(form).on('change', 'select[name$="[itemtype]"]', function () {
      const row    = this.closest('tr');
      const field  = $(row).find('select[name$="[value]"]');
      const params = JSON.parse(row.dataset.fieldParams);

      field.prop('disabled', true);
      $.post(url, {
         itemtype:        this.value,
         primary_type:    params.primary_type,
         called_by:       params.called_by,
         mapping_or_info: params.mapping_or_info,
      }).done((data) => {
         field.empty();
         data.fields.forEach(([key, label]) => field.append(new Option(label, key)));
         field.val(data.value);
      }).always(() => {
         field.prop('disabled', false).trigger('change');
      });
   });

   $(form).on('change', 'select[name$="[value]"]', function () {
      updateRow(this.closest('tr'));
   });
}

/**
 * Keep the counters badges (mapped fields, link fields, conflicts) of a mappings / infos form up to date.
 *
 * @param {HTMLFormElement} form
 * @param {Object} labels   counter name => [singular, plural] labels, containing "%d"
 */
function initMappingsCounters(form, labels) {
   if (!form) {
      return;
   }

   const counters = {
      mapped: () => Array.from(form.querySelectorAll('select[name$="[value]"]'))
         .filter((select) => select.value !== '' && select.value !== 'none').length,
      link: () => form.querySelectorAll('input[name$="[is_mandatory]"]:checked:not(:disabled)').length,
      conflict: () => form.querySelectorAll('[data-field-conflict]:not(.invisible)').length,
   };

   const refresh = () => {
      Object.keys(counters).forEach((name) => {
         const badge = form.querySelector(`[data-mappings-counter="${name}"]`);
         if (badge && labels[name]) {
            const count = counters[name]();
            badge.querySelector('[data-counter-text]').textContent = labels[name][count === 1 ? 0 : 1].replace('%d', count);
            if (badge.hasAttribute('data-hide-empty')) {
               badge.classList.toggle('d-none', count === 0);
            }
         }
      });
   };

   $(form).on('change', refresh);
   refresh();
}

/**
 * Enable the selection actions of a mappings / infos form only when rows are selected.
 *
 * @param {HTMLFormElement} form
 */
function initSelectionActions(form) {
   const container = form ? form.querySelector('[data-selection-actions]') : null;
   if (!container) {
      return;
   }

   const refresh = () => {
      const has_selection = form.querySelector('input[name^="item["]:checked') !== null;
      container.querySelectorAll('button').forEach((button) => {
         button.disabled = !has_selection;
      });
   };

   $(form).on('change', 'input[name^="item["]', refresh);
   refresh();
}
