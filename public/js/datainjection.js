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
 * @param num
 */
function show_log(num) {

   if (document.getElementById('log'+num+'_table').style.display == 'none') {
      document.getElementById('log'+num).src = '../pics/minus.png';
      document.getElementById('log'+num+'_table').style.display = 'block';
   } else {
      document.getElementById('log'+num).src = '../pics/plus.png';
      document.getElementById('log'+num+'_table').style.display = 'none';
   }
}

document.addEventListener('click', (e) => {
   const markLink = e.target.closest('[data-datainjection-mark-checkboxes]');
   if (markLink !== null) {
      if (markCheckboxes(markLink.dataset.datainjectionMarkCheckboxes)) {
         e.preventDefault();
      }
      return;
   }

   const unmarkLink = e.target.closest('[data-datainjection-unmark-checkboxes]');
   if (unmarkLink !== null) {
      if (unMarkCheckboxes(unmarkLink.dataset.datainjectionUnmarkCheckboxes)) {
         e.preventDefault();
      }
      return;
   }

   const popupLink = e.target.closest('[data-datainjection-popup]');
   if (popupLink !== null) {
      const popup = window.open(
         popupLink.dataset.datainjectionPopup,
         'glpipopup',
         popupLink.dataset.datainjectionPopupFeatures
      );
      popup.focus();
      e.preventDefault();
      return;
   }

   const locationLink = e.target.closest('[data-datainjection-location]');
   if (locationLink !== null) {
      location.href = locationLink.dataset.datainjectionLocation;
      return;
   }

   if (e.target.closest('[data-datainjection-close-window]') !== null) {
      e.preventDefault();
      window.close();
   }
});
