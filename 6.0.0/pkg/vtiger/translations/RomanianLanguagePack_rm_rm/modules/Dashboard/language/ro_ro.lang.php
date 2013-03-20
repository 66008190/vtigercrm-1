<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Dashboard/language/en_us.lang.php,v 1.4 2005/01/25 06:01:38 jack Exp $
 * Description:  Defines the Romanian language pack for the Account module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Magister Software SRL, Bucharest, Romania, www.magister.ro
 ********************************************************************************/
 
$mod_strings = Array(
'LBL_SALES_STAGE_FORM_TITLE'=>'Total vanzari pe etape',
'LBL_SALES_STAGE_FORM_DESC'=>'Afiseaza cantitatile cumulate din oportunitati pe etape de vanzare selectate pentru un utilizator anume daca data inchiderii apartine perioadei din timpul ramas.',
'LBL_MONTH_BY_OUTCOME'=>'Rezultat lunar vanzari',
'LBL_MONTH_BY_OUTCOME_DESC'=>'Afiseaza cantitatile cumulate din oportunitati pe luna si pe categorie pentru utilizatorii selectati cand data inchiderii se include in perioada de timp specificata. Rezultatul depinde de alegerea etapei de vanzare.',
'LBL_LEAD_SOURCE_FORM_TITLE'=>'Total portofel prin sursa de prospectare',
'LBL_LEAD_SOURCE_FORM_DESC'=>'Afiseaza cantitatile cumulate prin sursa de prospectare selectata pentru utilizatorii selectati.',
'LBL_LEAD_SOURCE_BY_OUTCOME'=>'Toate oportunitatile pe categorii',
'LBL_LEAD_SOURCE_BY_OUTCOME_DESC'=>'Afiseaza cantitatile cumulate a oportunitatilor prin sursa de prospectare aleasa si prin categorie pentru utilizatorii selectati cand data inchiderii se include in perioada aleasa.  Rezultatul se bazeaza pe stadiul vanzarilor atunci cand este Afacere castigata, Afacere pierduta sau orice alta valoare.',
'LBL_PIPELINE_FORM_TITLE_DESC'=>'Afiseaza cantitatile cumulate pe etapele de vanzare selectate pentru oportunitati cand data inchiderii se include in perioada datelor specificate.',
'LBL_DATE_RANGE'=>'Pentru perioada',
'LBL_DATE_RANGE_TO'=>'Catre',
'ERR_NO_OPPS'=>'Creaza oportunitati pentru a vedea graficele oportunitatilor.',
'LBL_TOTAL_PIPELINE'=>'Total in portofel',
'LBL_ALL_OPPORTUNITIES'=>'Total cantitate oportunitati',
'LBL_OPP_SIZE'=>'Volum in ',
'LBL_OPP_SIZE_VALUE'=>'1K',
'NTC_NO_LEGENDS'=>'Zero legende',
'LBL_LEAD_SOURCE_OTHER'=>'Alta sursa',
'LBL_EDIT'=>'Editeaza',
'LBL_REFRESH'=>'Refresh',
'LBL_CREATED_ON'=>'Lansat ultima oara la data de',
'LBL_OPPS_IN_STAGE'=>'oportunitati unde etapa de vanzare este',
'LBL_OPPS_IN_LEAD_SOURCE'=>'oportunitati unde sursa de prospectare este',
'LBL_OPPS_OUTCOME'=>'oportunitati unde categoria este',
'LBL_USERS'=>'Utilizatori:',
'LBL_SALES_STAGES'=>'Etape vanzare:',
'LBL_LEAD_SOURCES'=>'Sursa prospectari:',
'LBL_DATE_START'=>'Data inceput:',
'LBL_DATE_END'=>'Data final:',
//Added for 5.0 
'LBL_NO_PERMISSION'=>'Ne pare rau, nu aveti acces la vizualizarea graficului pentru acest modul',
'LBL_NO_PERMISSION_FIELD'=>'Ne pare rau, nu aveti acces la vizualizarea graficului pentru acest modul sau pentru acest camp',

"leadsource" => "Prospectari pe sursa",
"leadstatus" => "Prospectari pe stare",
"leadindustry" => "Prospectari pe industrie",
"salesbyleadsource" => "Vanzari pe sursa prospectari",
"salesbyaccount" => "Vanzari pe conturi",
"salesbyuser" => "Vanzari pe utilizator",
"salesbyteam"=>"Vanzari pe echipa",
"accountindustry" => "Cont pe industrie",
"productcategory" => "Produse pe categorie",
"productbyqtyinstock" => "Produse pe cantitatea din stoc",
"productbypo" => "Produse pe ordin cumparare",
"productbyquotes" => "Produse pe oferte",
"productbyinvoice" => "Produse pe factura",
"sobyaccounts" => "Comenzi vanzare pe conturi",
"sobystatus" => "Comenzi vanzare dupa stare",
"pobystatus" => "Comenzi cumparare dupa stare",
"quotesbyaccounts" => "Oferte pe conturi",
"quotesbystage" => "Oferte pe stadiu",
"invoicebyacnts" => "Facturi pe conturi",
"invoicebystatus" => "Facturi pe stare",
"ticketsbystatus" => "Tichete dupa stare",
"ticketsbypriority" => "Tichete dupa prioritate",
"ticketsbycategory" => "Tichete dupa categorie",
"ticketsbyuser"=>"Tichete pe utilizator",
"ticketsbyteam"=>"Tichete pe echipa",
"ticketsbyproduct"=>"Tichete pe produs",
"contactbycampaign"=>"Contacte pe campanie",
"ticketsbyaccount"=>"Tichete pe cont",
"ticketsbycontact"=>"Tichete pe contact",

'LBL_DASHBRD_HOME'=>'Index Tablou de Bord',
'LBL_HORZ_BAR_CHART'=>'Tabel bara orizontala',
'LBL_VERT_BAR_CHART'=>'Tabel bara verticala',
'LBL_PIE_CHART'=>'Tabel circular',
'LBL_NO_DATA'=>'Date indisponibile',
'DashboardHome'=>'Index Tablou de Bord',
'GRIDVIEW'=>'Tabel pe coloane',
'NORMALVIEW'=>'Vizualizare normala',
'VIEWCHART'=>'Vizualizeaza tabel',
'LBL_DASHBOARD'=>'Tablou de Bord',

// Added/Updated for vtiger CRM 5.0.4
"Approved"=>"Aprobat",
"Created"=>"Creat",
"Cancelled"=>"Anulat",
"Delivered"=>"Livrat",
"Received Shipment"=>"Marfa primita",
"Sent"=>"Trimis",
"Credit Invoice"=>"Factura credit",
"Paid"=>"Platit",
"Un Assigned"=>"Neasignat",
"Cold Call"=>"Apel rece",
"Existing Customer"=>"Client existent",
"Self Generated"=>"Generat automat",
"Employee"=>"Angajat",
"Partner"=>"Partener",
"Public Relations"=>"Relatii publice",
"Direct Mail"=>"Mail direct",
"Conference"=>"Conferinta",
"Trade Show"=>"Expozitie",
"Web Site"=>"Site Web",
"Word of mouth"=>"Recomandare verbala",
"Other"=>"Altceva",
"--None--"=>"Zero",
"Attempted to Contact"=>"Am incercat sa iau legatura",
"Cold"=>"Rece",
"Contact in Future"=>"Contacteaza in viitor",
"Contacted"=>"Contactat",
"Hot"=>"Fierbinte",
"Junk Lead"=>"Prospectare ineficienta",
"Lost Lead"=>"Prospectare pierduta",
"Not Contacted"=>"Nu a fost contactat",
"Pre Qualified"=>"Precalificat",
"Qualified"=>"Calificat",
"Warm"=>"Cald",
"Apparel"=>"Echipamente",
"Banking"=>"Bancar",
"Biotechnology"=>"Biotehnologie",
"Chemicals"=>"Chimicale",
"Communications"=>"Comunicatii",
"Construction"=>"Constructii",
"Consulting"=>"Consultanta",
"Education"=>"Educatie",
"Electronics"=>"Electronice",
"Energy"=>"Energie",
"Engineering"=>"Inginerie",
"Entertainment"=>"Divertisment",
"Environmental"=>"Mediu",
"Finance"=>"Finante",
"Food & Beverage"=>"Produse alimentare & bauturi",
"Government"=>"Guvern",
"Healthcare"=>"Medical",
"Hospitality"=>"Ospitalier",
"Insurance"=>"Asigurari",
"Machinery"=>"Masini",
"Manufacturing"=>"Productie",
"Media"=>"Media",
"Not For Profit"=>"Non Profit",
"Recreation"=>"Relaxare",
"Retail"=>"Retail",
"Shipping"=>"Transport de marfa",
"Technology"=>"Tecnologie",
"Telecommunications"=>"Telecomunicatii",
"Transportation"=>"Transport",
"Utilities"=>"Utilitati",
"Hardware"=>"Hardware",
"Software"=>"Software",
"CRM Applications"=>"Aplicatii CRM",
"Open"=>"Deschis",
"In Progress"=>"In desfasurare",
"Wait For Response"=>"Asteapta raspuns",
"Closed"=>"Inchis",
"Low"=>"Scazut",
"Normal"=>"Normal",
"High"=>"Ridicat",
"Urgent"=>"Urgent",
"Big Problem"=>"Problema grava",
"Small Problem"=>"Problema mai putin grava",
"Other Problem"=>"Alta problema",
"Accepted"=>"Acceptat",
"Rejected"=>"Respins",
"Prospecting"=>"Prospectare",
"Qualification"=>"Calificari",
"Needs Analysis"=>"Analiza necesitati",
"Value Proposition"=>"Propuneri de valoare",
"Id. Decision Makers"=>"ID persoane de decizie",
"Perception Analysis"=>"Analiza perceptie",
"Proposal/Price Quote"=>"Oferte propunere/pret",
"Negotiation/Review"=>"Negociere/Revizuire",
"Closed Won"=>"Afacere incheiata cu succes",
"Closed Lost"=>"Afacere incheiata in pierdere",

);

?>
