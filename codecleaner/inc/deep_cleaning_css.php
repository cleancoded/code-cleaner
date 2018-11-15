<?php
echo "<style>
html, body {
	overflow: hidden !important;
}
#codecleaner-deep-cleaning-tabs button:hover {
	background: #ffffff;
	color: #4A89DD;
}
#codecleaner-deep-cleaning {
	background: #EEF2F5;
	padding: 20px 20px 20px 320px;
	font-size: 14px;
	line-height: 1.5em;
	color: #4a545a;
	min-height: 100%;
}
#codecleaner-deep-cleaning-wrapper {
	display: none;
	position: fixed;
	z-index: 99999999;
	top: 32px;
	bottom: 0px;
	left: 0px;
	right: 0px;
	background: rgba(0,0,0,0.5);
	overflow-y: auto;
}
#codecleaner-deep-cleaning-tabs button.active {
	background: #4A89DD;
	color: #ffffff;
}
#codecleaner-deep-cleaning-tabs {
	overflow: hidden;
}
#codecleaner-deep-cleaning-header h2 span {
	background: #ED5464;
	color: #ffffff;
	padding: 5px;
	vertical-align: middle;
	font-size: 10px;
	margin-left: 5px;
}
#codecleaner-deep-cleaning-header {
	position: fixed;
	top: 32px;
	left: 0px;
	bottom: 0px;
	width: 300px;
	background: #282E34;
}
#codecleaner-deep-cleaning-header h2 {
	font-size: 24px;
	margin: 0px 0px 10px 0px;
	color: #4a545a;
	font-weight: bold;
}
#codecleaner-deep-cleaning-header #codecleaner-logo {
	display: block;
	margin: 20px auto;
	width: 200px;
}
#codecleaner-deep-cleaning a {
	color: #4A89DD;
	text-decoration: none;
	border: none;
}
#codecleaner-deep-cleaning-header p {
	font-size: 14px;
	color: #4a545a;
	font-style: italic;
	margin: 0px auto 15px auto;
}
#codecleaner-deep-cleaning-close {
	position: absolute;
	top: 0px;
	right: 0px;
	height: 26px;
	width: 26px;
}
#codecleaner-deep-cleaning-close img {
	height: 26px;
	width: 26px;
}
#codecleaner-deep-cleaning-tabs button {
	display: block;
	float: left;
	padding: 15px 45px;
	width: 100%;
	font-size: 17px;
	line-height: normal;
	text-align: center;
	background: #222222;
	color: #ffffff;
	font-weight: normal;
}
#codecleaner-deep-cleaning-tabs button span {
	display: block;
	font-size: 12px;
	margin-top: 5px;
}
#codecleaner-deep-cleaning-tabs button.active:hover {
	background: #4A89DD;
	color: #ffffff;
}
#codecleaner-deep-cleaning-disclaimer {
	background: #ffffff;
	padding: 20px 20px 10px 20px;
	margin: 0px 0px 20px 0px;
}
#codecleaner-deep-cleaning-disclaimer p {
	font-size: 14px;
	margin: 0px 0px 10px 0px;
}
#codecleaner-deep-cleaning-container {
	max-width: 1000px;
	margin: 0px auto;
}
#codecleaner-deep-cleaning h3 {
	padding: 10px;
	margin: 0px;
	font-size: 18px;
	background: #282E34;
	color: #ffffff;
	text-transform: capitalize;
	font-weight: 400;
}
#codecleaner-deep-cleaning-container .codecleaner-deep-cleaning-title-bar {
	margin-bottom: 13px;
	text-align: center;
}
.codecleaner-deep-cleaning-group h4 {
	font-size: 24px;
	line-height: 40px;
	margin: 0px;
	padding: 10px;
	background: #edf3f9;
	font-weight: 700;
}
.codecleaner-deep-cleaning-section {
	padding: 10px;
	background: #ffffff;
	margin: 0px 0px 0px 0px;
}
.codecleaner-deep-cleaning-group {
	box-shadow: 0 1px 6px 0 rgba(40,46,52,.3);
	margin: 0px 0px 20px 0px;
}
#codecleaner-deep-cleaning-container .codecleaner-deep-cleaning-title-bar h1 {
	font-size: 28px;
	font-weight: 400;
	margin: 0px;
	color: #282E34;
}
#codecleaner-deep-cleaning-container .codecleaner-deep-cleaning-title-bar p {
	margin: 0px;
	color: #282E34;
}
#codecleaner-deep-cleaning table {
	table-layout: fixed;
	width: 100%;
	margin: 0px;
	padding: 0px;
	border: none;
	text-align: left;
	font-size: 14px;
	border-collapse: collapse;
}
#codecleaner-deep-cleaning table thead {
	background: none;
	color: #282E34;
	font-weight: bold;
	border: none;
}
#codecleaner-deep-cleaning table thead tr {
	border: none;
	border-bottom: 2px solid #dddddd;
}
#codecleaner-deep-cleaning table tr {
	border: none;
	border-bottom: 1px solid #eeeeee;
	background: #ffffff;
}
#codecleaner-deep-cleaning table thead th {
	font-size: 14px;
	padding: 8px 5px;
	vertical-align: middle;
	border: none;
}
#codecleaner-deep-cleaning table td.codecleaner-deep-cleaning-type {
	font-size: 14px;
	text-align: center;
	padding-top: 16px;
	text-transform: uppercase;
}
#codecleaner-deep-cleaning table td {
	padding: 8px 5px;
	border: none;
	vertical-align: top;
	font-size: 14px;
}
#codecleaner-deep-cleaning table td.codecleaner-deep-cleaning-size {
	font-size: 14px;
	text-align: center;
	padding-top: 16px;
}
#codecleaner-deep-cleaning table td.codecleaner-deep-cleaning-script a {
	white-space: nowrap;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-disable, #codecleaner-deep-cleaning .codecleaner-deep-cleaning-enable {
	margin: 10px 0px 0px 0px; 
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-script a {
	display: block;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	font-size: 10px;
	color: #4A89DD;
	line-height: normal;
}
#codecleaner-deep-cleaning table tbody tr:last-child {
	border-bottom: 0px;
}
#codecleaner-deep-cleaning table .codecleaner-deep-cleaning-disable *:after, #codecleaner-deep-cleaning table .codecleaner-deep-cleaning-disable *:before {
	display: none;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-script span {
	display: block;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	font-size: 14px;
	font-weight: bold;
	margin-bottom: 3px;
} 
#codecleaner-deep-cleaning select {
	display: block; 
	position: relative;
	height: auto;
	width: auto;
	background: #ffffff;
	background-color: #ffffff;
	padding: 7px 10px;
	margin: 0px;
	font-size: 14px;
	appearance: menulist;
	-webkit-appearance: menulist;
	-moz-appearance: menulist;
}
#codecleaner-deep-cleaning select.codecleaner-disable-select.everywhere, #codecleaner-deep-cleaning select.codecleaner-status-select.disabled {
	border: 2px solid #ED5464;
}
#codecleaner-deep-cleaning select.codecleaner-disable-select, #codecleaner-deep-cleaning select.codecleaner-status-select {
	border: 2px solid #27ae60;
}
#codecleaner-deep-cleaning select.codecleaner-disable-select.current {
	border: 2px solid #f1c40f;
}
#codecleaner-deep-cleaning select.codecleaner-disable-select.hide {
	display: none;
} 
#codecleaner-deep-cleaning input[type='radio'] {
	position: relative;
	display: inline-block;
	margin: 0px 3px 0px 0px;
	vertical-align: middle;
	opacity: 1;
	appearance: radio;
	-webkit-appearance: radio;
	-moz-appearance: radio;
} 
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-enable-placeholder {
	color: #bbbbbb;
	font-style: italic;
	font-size: 14px;
}
#codecleaner-deep-cleaning input[type='checkbox'] {
	position: relative;
	display: inline-block;
	margin: 0px 3px 0px 0px;
	vertical-align: middle;
	opacity: 1;
	appearance: checkbox;
	-webkit-appearance: checkbox;
	-moz-appearance: checkbox;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-controls label {
	display: inline-block;
	margin: 0px 10px 0px 0px;
	width: auto;
	font-size: 12px;
}
/* On/Off Toggle Switch */
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch {
	position: relative;
	display: block;
	/*width: 48px;
	height: 28px;*/
	width: 76px;
	height: 40px;
	font-size: 1px;
} 
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #27ae60;
	-webkit-transition: .4s;
	transition: .4s;
}
#codecleaner-deep-cleaning input[type='submit'] {
	background: #4a89dd;
	color: #ffffff;
	cursor: pointer;
	border: none;
	font-size: 14px;
	margin: 10px auto 0px auto;
	padding: 15px 20px;
	font-weight: 700;w
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch input[type='checkbox'] {
	display: block;
	margin: 0px;
} 
#codecleaner-deep-cleaning input[type='submit']:hover {
	background: #5A93E0;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-slider:before {
	position: absolute;
	content: '';
	/*height: 20px;
	width: 20px;
	right: 4px;
	bottom: 4px;*/
	width: 30px;
	top: 5px;
	right: 5px;
	bottom: 5px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch input:checked + .codecleaner-deep-cleaning-slider:before {
	/*-webkit-transform: translateX(-20px);
	-ms-transform: translateX(-20px);
	transform: translateX(-20px);*/
	-webkit-transform: translateX(-36px);
	-ms-transform: translateX(-36px);
	transform: translateX(-36px);
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch input:checked + .codecleaner-deep-cleaning-slider {
	background-color: #ED5464;
}

#codecleaner-deep-cleaning .codecleaner-deep-cleaning-slider:after {
	content:'" . __('ON', 'codecleaner') . "';
	color: white;
	display: block;
	position: absolute;
	transform: translate(-50%,-50%);
	top: 50%;
	left: 27%;
	font-size: 10px;
	font-family: Verdana, sans-serif;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch input:focus + .codecleaner-deep-cleaning-slider {
	box-shadow: 0 0 1px #ED5464;
}

#codecleaner-deep-cleaning .codecleaner-deep-cleaning-assets-disabled p {
	margin: 20px 0px 0px 0px;
	text-align: center;
	font-size: 12px;
	padding: 10px 0px 0px 0px;
	border-top: 1px solid #f8f8f8;
}
/*Settings View*/
#deep-cleaning-settings table th {
	width: 200px;
	vertical-align: top;
	border: none;
}
#deep-cleaning-settings .switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 28px;
  font-size: 1px;
}
#codecleaner-deep-cleaning .codecleaner-deep-cleaning-switch input:checked + .codecleaner-deep-cleaning-slider:after {  
	left: unset;
	right: 0%;
  	content:'" . __('OFF', 'codecleaner') . "';
}
#deep-cleaning-settings input:checked + .slider {
  background-color: #2196F3;
}

#deep-cleaning-settings .switch input {
  display: block;
  margin: 0px;
}
#deep-cleaning-settings .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
#deep-cleaning-settings .slider:before {
  position: absolute;
  content: '';
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

#deep-cleaning-settings input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}
#deep-cleaning-settings input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
#jquery-message {
	font-size: 12px;
	font-style: italic;
	color: #27ae60;
	margin-top: 5px;
}
@media (max-width: 800px) {
	#codecleaner-deep-cleaning {
		padding-left: 20px;
	}
	#codecleaner-deep-cleaning-header {
		position: relative;
		top: 0px;
		width: 100%;
		overflow: hidden;
		margin-bottom: 20px;
	}
}
</style>";