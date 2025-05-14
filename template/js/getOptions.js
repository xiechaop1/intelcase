if(mobile_sections){
    // $('select').html()
    var options = ''
    for(var key in mobile_sections){
       // options += `<option value="${key}">${mobile_sections[key]}</option>`
        options += `<option value="${key}">${key}</option>`
    }
    $('select[name="sections"],select[name="mobile_section"],select[name="mobileSection"]').html(options)
}