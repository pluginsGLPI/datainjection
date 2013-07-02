<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
$LANG['datainjection']['name'][1] = "File injection";

$LANG['datainjection']['choiceStep'][6] = "Usar um modelo existente";

$LANG['datainjection']['model'][4]  = "Tipo de dado para importação";
$LANG['datainjection']['model'][5]  = "Tipo de arquivo";
$LANG['datainjection']['model'][6]  = "Permitir criação de linhas";
$LANG['datainjection']['model'][7]  = "Permitir atualização de linhas";
$LANG['datainjection']['model'][8]  = "Permitir criação de dropdowns";
$LANG['datainjection']['model'][9]  = "Cabeçalho presente";
$LANG['datainjection']['model'][10] = "Delimitador do arquivo";
$LANG['datainjection']['model'][12] = "Permitir atualização de campos existentes";
$LANG['datainjection']['model'][15] = "Opções avançadas";
$LANG['datainjection']['model'][18] = "Privado";
$LANG['datainjection']['model'][20] = "Tentar estabelecer uma conexão de rede se possível";
$LANG['datainjection']['model'][21] = "Formato de data";
$LANG['datainjection']['model'][22] = "dd-mm-yyyy";
$LANG['datainjection']['model'][23] = "mm-dd-yyyy";
$LANG['datainjection']['model'][24] = "yyyy-mm-dd";
$LANG['datainjection']['model'][25] = "1 234.56";
$LANG['datainjection']['model'][26] = "1 234,56";
$LANG['datainjection']['model'][27] = "1,234.56";
$LANG['datainjection']['model'][28] = "Float format";
$LANG['datainjection']['model'][29] = "Formato de data especifico";
$LANG['datainjection']['model'][30] = "Por favor, entre com um nome para o modelo";
$LANG['datainjection']['model'][31] = "Seu modelo deve permitir a importação e/ou atualização dos dadosâ€";
$LANG['datainjection']['model'][32] = "O arquivos está ok.".
                     "<br>Você pode começar a fazer o mapeamento com os campos do GLPIâ€";
$LANG['datainjection']['model'][33] = "Nenhum modelo disponível atualmente";
$LANG['datainjection']['model'][34] = "Você pode iniciar a criação do modelo, pressionando o botão";
$LANG['datainjection']['model'][35] = "Criação do modelo em curso";
$LANG['datainjection']['model'][36] = "Modelo disponível para uso";
$LANG['datainjection']['model'][37] = "Validação";
$LANG['datainjection']['model'][38] = "Validar o modelo";
$LANG['datainjection']['model'][39] = "Lista de modelos";
$LANG['datainjection']['model'][40] = "Fazer download de arquivo de exemplo";

$LANG['datainjection']['fileStep'][3]  = "Escolha um arquivo";
$LANG['datainjection']['fileStep'][4]  = "O arquivo não pode ser encontrado";
$LANG['datainjection']['fileStep'][5]  = "Formato do arquivo está errado";
$LANG['datainjection']['fileStep'][6]  = "Extensão";
$LANG['datainjection']['fileStep'][7]  = "Requerido";
$LANG['datainjection']['fileStep'][8]  = "Impossível copiar o arquivo em";
$LANG['datainjection']['fileStep'][9]  = "File encoding";
$LANG['datainjection']['fileStep'][10] = "Detecção automática";
$LANG['datainjection']['fileStep'][11] = "UTF-8";
$LANG['datainjection']['fileStep'][12] = "ISO8859-1";
$LANG['datainjection']['fileStep'][13] = "Abrir este arquivo";

$LANG['datainjection']['mapping'][2]  = "Cabeçalho do arquivo";
$LANG['datainjection']['mapping'][3]  = "Tabelas";
$LANG['datainjection']['mapping'][4]  = "Campos";
$LANG['datainjection']['mapping'][5]  = "Relacionar Campos";
$LANG['datainjection']['mapping'][6]  = "-------Escolha uma tabela-------";
$LANG['datainjection']['mapping'][7]  = "-------Escolha um campo-------";
$LANG["datainjection"]["mapping"][8]  = "Conectado em : nome do dispositivo";
$LANG["datainjection"]["mapping"][9]  = "Conectado em : número da porta";
$LANG["datainjection"]["mapping"][10] = "Conectado em : porta MAC address";
$LANG['datainjection']['mapping'][11] = "Um campo de relação deve ser selecionado :<br> ele será usado para verificar se os dados já existem no sistema";
$LANG['datainjection']['mapping'][13] = "Atenção : os dados existentes serão sobrescritos";

$LANG['datainjection']['info'][1] = "Informação complementar";
$LANG['datainjection']['info'][3] = "Este passo permite que você adicione informações que não estão presentes no arquivo. Estas informações serão solicitadas quando você estiver usando o modelo.";
$LANG['datainjection']['info'][5] = "Informação obrigatória";

$LANG['datainjection']['saveStep'][11] = "O número de colunas no arquivo está incorreto.";
$LANG['datainjection']['saveStep'][12] = "Uma das colunas está incorreta";
$LANG['datainjection']['saveStep'][17] = " Coluna(s) encontrada(s)";
$LANG['datainjection']['saveStep'][18] = " No arquivo : ";
$LANG['datainjection']['saveStep'][19] = " Do modelo : ";

$LANG['datainjection']['fillInfoStep'][1] = "Cuidado, você está preste a injetar dados no GLPI. Você está certo de que deseja prosseguir?";
$LANG['datainjection']['fillInfoStep'][3] = "* campo mandatório";
$LANG['datainjection']['fillInfoStep'][5] = "Um campo obrigatório não foi preenchido, verifique";

$LANG['datainjection']['importStep'][1] = "Injetando o arquivo";
$LANG['datainjection']['importStep'][3] = "Inserção finalizada";

$LANG['datainjection']['log'][1]  = "Resultado da inserção";
$LANG['datainjection']['log'][3]  = "Inserção concluída com sucesso";
$LANG['datainjection']['log'][4]  = "Lista de inserções bem sucedidas";
$LANG['datainjection']['log'][5]  = "Lista de inserções com falhas";
$LANG['datainjection']['log'][8]  = "Erros encontrados";
$LANG['datainjection']['log'][9]  = "Checar dados";
$LANG['datainjection']['log'][10] = "Importar Dados";
$LANG['datainjection']['log'][11] = "Tipo de inserção";
$LANG['datainjection']['log'][12] = "Identificador do objeto";
$LANG['datainjection']['log'][13] = "Linha";

$LANG['datainjection']['button'][3] = "Veja o arquivo";
$LANG['datainjection']['button'][4] = "Veja o log";
$LANG['datainjection']['button'][5] = "Exportar o log";
$LANG['datainjection']['button'][6] = "Finalizar";
$LANG['datainjection']['button'][7] = "Exportar relatório em PDF";
$LANG['datainjection']['button'][8] = "Fechar";

$LANG['datainjection']['result'][2]  = "Dados a inserir estão corretos";
$LANG['datainjection']['result'][4]  = "Pelo menos um dos campos obrigatórios não está presente, verifique";
$LANG['datainjection']['result'][6]  = "Impossível determinar";
$LANG['datainjection']['result'][7]  = "Importação realizada com sucesso";
$LANG['datainjection']['result'][8]  = "Adicionar";
$LANG['datainjection']['result'][9]  = "Atualizar";
$LANG['datainjection']['result'][10] = "Successo";
$LANG['datainjection']['result'][11] = "Falha";
$LANG['datainjection']['result'][12] = "Atenção";
$LANG['datainjection']['result'][17] = "Nenhum data para inserir";
$LANG['datainjection']['result'][18] = "Relatório do File Injection";
$LANG['datainjection']['result'][21] = "Importação é impossível";
$LANG['datainjection']['result'][22] = "Tipo de arquivo desconhecido";
$LANG['datainjection']['result'][23] = "Um campo mandatório está faltando";

$LANG['datainjection']['result'][30] = "O dado já existe";
$LANG['datainjection']['result'][31] = "Sem direitos para importação dos dados";
$LANG['datainjection']['result'][32] = "Sem direitos para atualização dos dados";
$LANG['datainjection']['result'][33] = "Dados não encontrados";
$LANG['datainjection']['result'][34] = "Dados já utilizados";
$LANG['datainjection']['result'][35] = "Mais de um valor encontrado";
$LANG['datainjection']['result'][36] = "Objeto já está relacionado";
$LANG['datainjection']['result'][37] = "Tamanho máximo do campo excedido";
$LANG['datainjection']['result'][39] = "Import refused by the dictionnary";

$LANG['datainjection']['profiles'][1] = "Gerenciamento de modelos";

$LANG['datainjection']['mappings'][1] = "Números de portas";
$LANG['datainjection']['mappings'][7] = "Critério de unicidade de porta";

$LANG['datainjection']['history'][1] = "de um arquivo CSV";

$LANG['datainjection']['model'][0] = "Modelo";

$LANG['datainjection']['tabs'][0] = "Mapeamento";
$LANG['datainjection']['tabs'][1] = "Informações adicionais";
$LANG['datainjection']['tabs'][2] = "Valores fixados";
$LANG['datainjection']['tabs'][3] = "Arquivo para inserção";

$LANG['datainjection']['import'][0] = "Iniciar importação";
$LANG['datainjection']['import'][1] = "Progresso da importação";

$LANG['datainjection']['port'][1] = "Network link";
$LANG['datainjection']['entity'][1] = "Entity informations";

$LANG['datainjection']['install'][1] = "must exists and be writable for web server user";
?>