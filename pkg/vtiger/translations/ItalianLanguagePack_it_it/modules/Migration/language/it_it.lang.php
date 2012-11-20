<?php
$mod_strings = array (
  'LBL_MIGRATE_INFO' => 'Inserisci i Valori per Migrare i Dati da <b><i> Sorgente </i></b> a <b><i> Attuale (Piu Recente) vtigerCRM </i></b>',
  'LBL_CURRENT_VT_MYSQL_EXIST' => 'L&#39;attuale installazione MySQL di vTiger si trova su',
  'LBL_THIS_MACHINE' => 'Questo computer',
  'LBL_DIFFERENT_MACHINE' => 'Un altro computer',
  'LBL_CURRENT_VT_MYSQL_PATH' => 'Attuale percorso (path) MySQL di Vtiger',
  'LBL_SOURCE_VT_MYSQL_DUMPFILE' => 'Nome del Dump File del vTiger <b>Sorgente</b> ',
  'LBL_NOTE_TITLE' => 'Note:',
  'LBL_NOTES_LIST1' => 'Se il MySQL Attuale si trova stessa macchina inserisci il path MySQL,  oppure specifica il Dump file se lo hai.',
  'LBL_NOTES_LIST2' => 'Se il MySQL Attuale si trova su un&#39;altra Macchina inserisci il nome file di Dump (Sorgente) specificando il percorso completo.',
  'LBL_NOTES_DUMP_PROCESS' => 'Per estrarre il dump del Database esegui i seguenti comandi da dentro la cartella mysql/bin (cio&egrave; dalla directory dove risiedono i binari di MySQL<br><b>mysqldump --user=&#34;mysql_username&#34; --password=&#34;mysql-password&#34; -h &#34;hostname&#34; --port=&#34;mysql_port&#34; &#34;database_name&#34; > nomefile_dump </b><br> aggiungi <b>SET FOREIGN_KEY_CHECKS = 0; </b> all&#39;inizio del file di dump e aggiungi <b>SET FOREIGN_KEY_CHECKS = 1;</b> alla fine del file di dump',
  'LBL_NOTES_LIST3' => 'IIndica il percorso di MySQL nel formato <b>/home/crm/vtigerCRM4_5/mysql</b>',
  'LBL_NOTES_LIST4' => 'Indica il nome del file di dump con il percorso completo, come <b>/home/fullpath/4_2_dump.txt</b>',
  'LBL_CURRENT_MYSQL_PATH_FOUND' => 'Il percorso MySQL dell&#39;installazione Attuale &egrave; stato trovato.',
  'LBL_SOURCE_HOST_NAME' => 'Nome macchina Sorgente',
  'LBL_SOURCE_MYSQL_PORT_NO' => 'Porta MySql macchina sorgente :',
  'LBL_SOURCE_MYSQL_USER_NAME' => 'Nome utente MySql macchina Sorgente:',
  'LBL_SOURCE_MYSQL_PASSWORD' => 'Password MySql macchina Sorgente:',
  'LBL_SOURCE_DB_NAME' => 'Nome database MySql macchina Sorgente:',
  'LBL_MIGRATE' => 'Migra alla versione Attuale',
  'LBL_UPGRADE_VTIGER' => 'Aggiorna il Database di vTiger CRM ',
  'LBL_UPGRADE_FROM_VTIGER_423' => 'Aggiorna il DataBase da vTiger CRM 4.2.3 alla versione  5.0.0',
  'LBL_SETTINGS' => 'Impostazioni',
  'LBL_STEP' => 'Passo',
  'LBL_SELECT_SOURCE' => 'Seleziona Fonte',
  'LBL_STEP1_DESC' => 'Per iniziare la migrazione del DataBase, devi specificare il formato nel quale il vecchio database &egrave; disponibile',
  'LBL_RADIO_BUTTON1_TEXT' => 'Ho accesso al sistema database live di vtiger ',
  'LBL_RADIO_BUTTON1_DESC' => 'Questa opzione richiede che tu abbia l&#39;indirizzo della macchina host (dove il  DB risiede) e le credenziali di accesso al DB. Sia il sistema locale che remoti sono supportati con questo metodo. Fai riferimento alla documentazione per ulteriori informazioni.',
  'LBL_RADIO_BUTTON2_TEXT' => 'Ho accesso ad un dump archiviato di un database di vtiger CRM',
  'LBL_RADIO_BUTTON2_DESC' => 'Questa opzione richiede che il dump del database sia disponibile localmente, sulla stessa macchina su cui stai aggiornando. Non puoi accedere al dump del database da una macchina differente (database server remoto). Fai riferimento alla documentazione per ulteriori informazioni.',
  'LBL_RADIO_BUTTON3_TEXT' => 'Ho un database nuovo con i dati della versione 4.2.3',
  'LBL_RADIO_BUTTON3_DESC' => 'Questa opzione richiede i dettagli database vtiger CRM 4.2.3, incluso database server ID, user name, e password. Non puoi accedere al database dump da una macchina differente (database server remoto)',
  'LBL_HOST_DB_ACCESS_DETAILS' => 'Dettagli accesso database host',
  'LBL_MYSQL_HOST_NAME_IP' => 'MySQL Host Name o Indirizzo IP : ',
  'LBL_MYSQL_PORT' => 'MySQL Numero di Porta : ',
  'LBL_MYSQL_USER_NAME' => 'MySql User Name : ',
  'LBL_MYSQL_PASSWORD' => 'MySql Password : ',
  'LBL_DB_NAME' => 'Nome Database : ',
  'LBL_LOCATE_DB_DUMP_FILE' => 'Specifica il database dump file',
  'LBL_DUMP_FILE_LOCATION' => 'Posizione del File di Dump: ',
  'LBL_RADIO_BUTTON3_PROCESS' => '<font color="red">Non specificare i dettagli del database 4.2.3. Questa opzione modificher&agrave; direttamente e permanentemente il database selezionato.</font>. &#200; fortemente consigliato di fare un dump del database 4.2.3, creare un nuovo database, e applicare al nuovo database il dump del database 4.2.3. Questa migrazione modifica il database per farlo corrispondere allo schema della versione 5.0',
  'LBL_ENTER_MYSQL_SERVER_PATH' => 'Inserisci il percorso del Server MySQL',
  'LBL_SERVER_PATH_DESC' => 'Percorso dell&#39;installazione MySQL, es. <b>/home/5beta/vtigerCRM5_beta/mysql/bin</b> or <b>c:&#92;Programmi&#92;mysql&#92;bin</b>',
  'LBL_MYSQL_SERVER_PATH' => 'Percorso Server MySQL : ',
  'LBL_MIGRATE_BUTTON' => 'Migra',
  'LBL_CANCEL_BUTTON' => 'Annulla',
  'LBL_UPGRADE_FROM_VTIGER_5X' => 'Aggiorna il database da vTiger 5.x a una versione successiva',
  'LBL_PATCH_OR_MIGRATION' => 'devi specificare la versione del database di origine (aggiornamento da Patch o Migrazione)',
  'ENTER_SOURCE_HOST' => 'Prego inserire il nome Host di origine',
  'ENTER_SOURCE_MYSQL_PORT' => 'Prego inserire la porta MySql di origine',
  'ENTER_SOURCE_MYSQL_USER' => 'Prego inserire l&#39;utente MySql di origine',
  'ENTER_SOURCE_DATABASE' => 'Prego inserire il Database  MySql di origine',
  'ENTER_SOURCE_MYSQL_DUMP' => 'Prego inserire un file di dump Mysql valido',
  'ENTER_HOST' => 'Prego inserire il nome Host',
  'ENTER_MYSQL_PORT' => 'Prego inserire la porta MySql',
  'ENTER_MYSQL_USER' => 'Prego inserire l&#39;utente MySql',
  'ENTER_DATABASE' => 'Prego inserire il nome del Database',
  'SELECT_ANYONE_OPTION' => 'Prego selezionare un&#39;opzione',
  'ENTER_CORRECT_MYSQL_PATH' => 'Prego inserire il percorso MySql corretto',

);
?>