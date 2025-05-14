if(mobile_sections){
    // $('select').html()
    var options = ''
    for(var key in mobile_sections){
       options += `<option value="${key}">${mobile_sections[key]}</option>`
    }
    $('select[name="sections"]').html(options)
}