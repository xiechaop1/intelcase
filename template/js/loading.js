$(function () {
    var loading='<!-- loading -->\n' +
        '<div id="loadingModal" class="modal-content h-100 z-9999   fixed top-0"  style="background-color: rgba(255,255,255,0.8)">\n' +
        '    <div class="w-100 m-height-100 d-flex justify-content-center pt-5 p-0">\n' +
        '        <div class="w-1200 text-FF">\n' +
        '            <div class="d-flex align-items-center mt-5 flex-column">\n' +
        '                <div class="mt-5">\n' +
        '                    <img src="../../img/loading.gif" class="img-responsive img-modal"/>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>';

    $("body").append(loading);

    $(document).ready(function() {
        $("#loadingModal").addClass('d-none');
    })


})