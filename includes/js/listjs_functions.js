

// var options = {
//   valueNames: [ 'id', 'display_name']
// };

// // Init list
// var contactList = new List('table_div', options);

// var idField = $('#id-field'),
//     nameField = $('#name-field'),
//     ageField = $('#age-field'),
//     cityField = $('#city-field'),
//     addBtn = $('#add-btn'),
//     editBtn = $('#edit-btn').hide(),
//     removeBtns = $('.remove-item-btn'),
//     editBtns = $('.edit-item-btn');

// // Sets callbacks to the buttons in the list
// refreshCallbacks();

// addBtn.click(function() {
//   contactList.add({
//     id: Math.floor(Math.random()*110000),
//     name: nameField.val(),
//     age: ageField.val(),
//     city: cityField.val()
//   });
//   clearFields();
//   refreshCallbacks();
// });

// editBtn.click(function() {
//   var item = contactList.get('id', idField.val())[0];
//   item.values({
//     id:idField.val(),
//     name: nameField.val(),
//     age: ageField.val(),
//     city: cityField.val()
//   });
//   clearFields();
//   editBtn.hide();
//   addBtn.show();
// });

// function refreshCallbacks() {
//   // Needed to add new buttons to jQuery-extended object
//   removeBtns = $(removeBtns.selector);
//   editBtns = $(editBtns.selector);
  
//   removeBtns.click(function() {
//     var itemId = $(this).closest('tr').find('.id').text();
//     contactList.remove('id', itemId);
//   });
  
//   editBtns.click(function() {
//     var itemId = $(this).closest('tr').find('.id').text();
//     var itemValues = contactList.get('id', itemId)[0].values();
//     idField.val(itemValues.id);
//     nameField.val(itemValues.name);
//     ageField.val(itemValues.age);
//     cityField.val(itemValues.city);
    
//     editBtn.show();
//     addBtn.hide();
//   });
// }

// function clearFields() {
//   nameField.val('');
//   ageField.val('');
//   cityField.val('');
// }




// <script type="text/javascript">
// /* 
//    Willmaster Table Sort
//    Version 1.1
//    August 17, 2016
//    Updated GetDateSortingKey() to correctly sort two-digit months and days numbers with leading 0.
//    Version 1.0, July 3, 2011

//    Will Bontrager
//    https://www.willmaster.com/
//    Copyright 2011,2016 Will Bontrager Software, LLC

//    This software is provided "AS IS," without 
//    any warranty of any kind, without even any 
//    implied warranty such as merchantability 
//    or fitness for a particular purpose.
//    Will Bontrager Software, LLC grants 
//    you a royalty free license to use or 
//    modify this software provided this 
//    notice appears on all copies. 
// */
// //
// // One placed to customize - The id value of the table tag.

// var TableIDvalue = "indextable";

// //
// //////////////////////////////////////
// var TableLastSortedColumn = -1;
// function SortTable() {
// var sortColumn = parseInt(arguments[0]);
// var type = arguments.length > 1 ? arguments[1] : 'T';
// var dateformat = arguments.length > 2 ? arguments[2] : '';
// var table = document.getElementById(TableIDvalue);
// var tbody = table.getElementsByTagName("tbody")[0];
// var rows = tbody.getElementsByTagName("tr");
// var arrayOfRows = new Array();
// type = type.toUpperCase();
// dateformat = dateformat.toLowerCase();
// for(var i=0, len=rows.length; i<len; i++) {
//   arrayOfRows[i] = new Object;
//   arrayOfRows[i].oldIndex = i;
//   var celltext = rows[i].getElementsByTagName("td")[sortColumn].innerHTML.replace(/<[^>]*>/g,"");
//   if( type=='D' ) { arrayOfRows[i].value = GetDateSortingKey(dateformat,celltext); }
//   else {
//     var re = type=="N" ? /[^\.\-\+\d]/g : /[^a-zA-Z0-9]/g;
//     arrayOfRows[i].value = celltext.replace(re,"").substr(0,25).toLowerCase();
//     }
//   }
// if (sortColumn == TableLastSortedColumn) { arrayOfRows.reverse(); }
// else {
//   TableLastSortedColumn = sortColumn;
//   switch(type) {
//     case "N" : arrayOfRows.sort(CompareRowOfNumbers); break;
//     case "D" : arrayOfRows.sort(CompareRowOfNumbers); break;
//     default  : arrayOfRows.sort(CompareRowOfText);
//     }
//   }
// var newTableBody = document.createElement("tbody");
// for(var i=0, len=arrayOfRows.length; i<len; i++) {
//   newTableBody.appendChild(rows[arrayOfRows[i].oldIndex].cloneNode(true));
//   }
// table.replaceChild(newTableBody,tbody);
// } // function SortTable()

// function CompareRowOfText(a,b) {
// var aval = a.value;
// var bval = b.value;
// return( aval == bval ? 0 : (aval > bval ? 1 : -1) );
// } // function CompareRowOfText()

// function CompareRowOfNumbers(a,b) {
// var aval = /\d/.test(a.value) ? parseFloat(a.value) : 0;
// var bval = /\d/.test(b.value) ? parseFloat(b.value) : 0;
// return( aval == bval ? 0 : (aval > bval ? 1 : -1) );
// } // function CompareRowOfNumbers()

// function GetDateSortingKey(format,text) {
// if( format.length < 1 ) { return ""; }
// format = format.toLowerCase();
// text = text.toLowerCase();
// text = text.replace(/^[^a-z0-9]*/,"");
// text = text.replace(/[^a-z0-9]*$/,"");
// if( text.length < 1 ) { return ""; }
// text = text.replace(/[^a-z0-9]+/g,",");
// var date = text.split(",");
// if( date.length < 3 ) { return ""; }
// var d=0, m=0, y=0;
// for( var i=0; i<3; i++ ) {
//   var ts = format.substr(i,1);
//   if( ts == "d" ) { d = date[i]; }
//   else if( ts == "m" ) { m = date[i]; }
//   else if( ts == "y" ) { y = date[i]; }
//   }
// d = d.replace(/^0/,"");
// if( d < 10 ) { d = "0" + d; }
// if( /[a-z]/.test(m) ) {
//   m = m.substr(0,3);
//   switch(m) {
//     case "jan" : m = String(1); break;
//     case "feb" : m = String(2); break;
//     case "mar" : m = String(3); break;
//     case "apr" : m = String(4); break;
//     case "may" : m = String(5); break;
//     case "jun" : m = String(6); break;
//     case "jul" : m = String(7); break;
//     case "aug" : m = String(8); break;
//     case "sep" : m = String(9); break;
//     case "oct" : m = String(10); break;
//     case "nov" : m = String(11); break;
//     case "dec" : m = String(12); break;
//     default    : m = String(0);
//     }
//   }
// m = m.replace(/^0/,"");
// if( m < 10 ) { m = "0" + m; }
// y = parseInt(y);
// if( y < 100 ) { y = parseInt(y) + 2000; }
// return "" + String(y) + "" + String(m) + "" + String(d) + "";
// } // function GetDateSortingKey()
// </script>

