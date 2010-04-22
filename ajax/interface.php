<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------
echo "Ext.onReady(function(){

    Ext.state.Manager.setProvider(
            new Ext.state.SessionProvider({state: Ext.appState}));

    var button = Ext.get('show-btn');

    button.on('click', function(){

        // tabs for the center
        var tabs = new Ext.TabPanel({
            region: 'center',
            margins:'3 3 3 0',
            activeTab: 0,
            defaults:{autoScroll:true},

            items:[{
                title: 'Bogus Tab',
                html: Ext.example.bogusMarkup
            },{
                title: 'Another Tab',
                html: Ext.example.bogusMarkup
            },{
                title: 'Closable Tab',
                html: Ext.example.bogusMarkup,
                closable:true
            }]
        });

        // Panel for the west
        var nav = new Ext.Panel({
            title: 'Navigation',
            region: 'west',
            split: true,
            width: 200,
            collapsible: true,
            margins:'3 0 3 3',
            cmargins:'3 3 3 3'
        });

        var win = new Ext.Window({
            title: 'Layout Window',
            closable:true,
            width:600,
            height:350,
            //border:false,
            plain:true,
            layout: 'border',

            items: [nav, tabs]
        });

        win.show(this);
    });
});";
?>
