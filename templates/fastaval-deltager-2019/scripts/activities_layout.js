"using strict";
// long name to avoid naming conflicts
var activities_time_header = {};

jQuery(document).ready(function(){
  jQuery(document).scroll(function(){
    var ath = activities_time_header; //shorter name for easier coding

    var header = jQuery('.fusion-header')[0];
    ath.header_bottom = header.getBoundingClientRect().bottom;

    if (ath.row !== undefined){
      if (ath.should_reset(ath.table)) {
        ath.row.parentNode.removeChild(ath.row)
        ath.row = undefined;
        ath.table = undefined;
        ath.original_row = undefined;
      }
    }


    var lowest = null;
    var new_row = null;
    var time_tables = jQuery('tr:nth-child(2)').toArray();
    for (var i = 0; i < time_tables.length; i++){
      var rect = time_tables[i].getBoundingClientRect();
      
      if (rect.top < ath.header_bottom){
        if (lowest === null || rect.top > lowest) {
          lowest = rect.top;
          new_row = time_tables[i];
        }
      }
    }

    if (new_row !== null){
      var table = new_row.parentElement;
      if (table === ath.table || ath.should_reset(table)) return;

      // save references for later
      ath.table = table;
      ath.original_row = new_row;

      // create a copy of the time header
      ath.row = new_row.cloneNode(true);
      ath.table.appendChild(ath.row);


      // allign the row with the bottom on the header menu
      jQuery(ath.row).css({top: ath.header_bottom, position: 'fixed', background: 'white'});
      ath.copy_widths();

      // save this for when we don't wanna show the row at the bottom of the table
      ath.last_row_bottom = ath.row.getBoundingClientRect().bottom;
    }
  })
});

activities_time_header.copy_widths = function(){
  jQuery(this.row).width(jQuery(this.original_row).width());

  var current_o_node = this.original_row.firstChild;
  var current_node = this.row.firstChild;
  do {
    if (current_node instanceof Element) {
     jQuery(current_node).width(jQuery(current_o_node).width());
    }
  } while (current_node = current_node.nextSibling, current_o_node = current_o_node.nextSibling)
}

activities_time_header.should_reset = function(table){
  // is the original time header going to be vissible
  var o_row_top = this.original_row !== undefined ? this.original_row.getBoundingClientRect().top : 0;
  var row_vissible =  o_row_top > this.header_bottom;
  // is the table still in view
  var row_bottom = this.row !== undefined ? this.row.getBoundingClientRect().bottom : this.last_row_bottom;
  var table_rect = table.getBoundingClientRect();
  var table_hidden = table_rect.bottom < row_bottom;
  return table_hidden || row_vissible;
} 