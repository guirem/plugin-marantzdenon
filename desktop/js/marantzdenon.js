
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */


 $("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
 function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
	// check if predefined command
	var predefCmd = ['ip','reachable','power_state', 'input', 'input_info', 'input_netinfo', 'netlogo',
					 'volume_set', 'volume', 'volume_up', 'volume_down',
					 'mute_state', 'mute_on', 'mute_off', 'sound_mode',
					 'on', 'off', 'sleep', 'sleepbtn', 'refresh'
					];

	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	//console.log('hh : ' + _cmd.logicalId);
	if ( (_cmd.logicalId != null) && ($.inArray(_cmd.logicalId, predefCmd)!=-1 || _cmd.logicalId.startsWith('fav_') || _cmd.logicalId.startsWith('si_')) ) {

		tr += '<td>';
		tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
		tr += '<span class="type" type="' + init(_cmd.type) + '" style="display:none;">' + jeedom.cmd.availableType() + '</span>';
		tr += '<span class="subType" subType="' + init(_cmd.subType) + '" style="display:none;"></span>';
		tr += '<div class="row">';
		tr += '<div class="col-sm-6">';
		tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
		tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
		tr += '</div>';
		tr += '<div class="col-sm-6">';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}">';
		tr += '</div>';
		tr += '</div>';
		tr += '</td>';
		tr += '<td> ' + init(_cmd.type) + '</td>';
		tr += '<td><i>Non modifiable</i></td>';
		tr += '<td>';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
		tr += '</td>';
		tr += '<td><i>Non modifiable</i></td>';
		tr += '<td>';
		if (is_numeric(_cmd.id)) {
			tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
			tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
		}
		tr += '</td>';
	}
	else {	// is new created command

		tr += '<td>';
		tr += '<div class="row">';
		tr += '<div class="col-sm-6">';
		tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
		tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
		tr += '</div>';
		tr += '<div class="col-sm-6">';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}">';
		tr += '</div>';
		tr += '</div>';
		//tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="La valeur de la commande vaut par défaut la commande">';
		//tr += '<option value="">Aucune</option>';
		tr += '</select>';
		tr += '</td>';
		tr += '<td>';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
		//tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
		tr += '<span class="type" type="' + init(_cmd.type) + '"><select style="width : 120px; margin-bottom : 3px;" class="cmdAttr form-control input-sm" data-l1key="type"><option value="action">Action</option></select></span>';
		tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
		tr += '</td>';
		tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="logicalId" style="width : 90%; display : inline-block;" placeholder="{{Commande}}"><br/>';
		tr += '</td>';
		tr += '<td>';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
		tr += '<br/><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateValue" placeholder="{{Valeur retour d\'état}}" style="width : 20%; display : inline-block;margin-top : 5px;margin-right : 5px;">';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateTime" placeholder="{{Durée avant retour d\'état (min)}}" style="width : 20%; display : inline-block;margin-top : 5px;margin-right : 5px;">';
		tr += '</td>';
		tr += '<td>';
		//tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="updateCmdId" style="display : none;margin-top : 5px;" title="Commande d\'information à mettre à jour">';
		//tr += '<option value="">Aucune</option>';
		//tr += '</select>';
		//tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="updateCmdToValue" placeholder="Valeur de l\'information" style="display : none;margin-top : 5px;">';
		//tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite"  style="width : 100px;" placeholder="Unité" title="Unité">';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="Min" title="Min"> ';
		tr += '<input class="cmdAttr form-control input-sm data-l1key="configuration" data-l2key="maxValue" placeholder="Max" title="Max" style="margin-top : 5px;">';
		tr += '</td>';
		tr += '<td>';
		if (is_numeric(_cmd.id)) {
			tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
			tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> Tester</a>';
		}
		tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
	}

	/*
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
	*/
	tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    var tr = $('#table_cmd tbody tr:last');
    jeedom.eqLogic.builSelectCmd({
        id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
        filter: {type: 'info'},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result) {
            tr.find('.cmdAttr[data-l1key=value]').append(result);
            tr.find('.cmdAttr[data-l1key=configuration][data-l2key=updateCmdId]').append(result);
            tr.setValues(_cmd, '.cmdAttr');
            jeedom.cmd.changeType(tr, init(_cmd.subType));
        }
    });
}
