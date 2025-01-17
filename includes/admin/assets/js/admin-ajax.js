jQuery(document).ready(function($) {

    // PopUp button
    jQuery('body').on('click', 'a.httemplateimp', function(e) {
        e.preventDefault();

        var $this = $(this),
            template_opt = $this.data('templpateopt');

        $('.httemplate-edit').html('');
        $('#htpagetitle').val('');
        $(".htpopupcontent").show();
        $(".htmessage").hide();
        $(".htmessage p").html( template_opt.message );

        // dialog header
        $("#httemplate-popup-area").attr( "title", template_opt.templpattitle );

        var htbtnMarkuplibrary = `<a href="#" class="htimpbtn" data-templateid="${template_opt.templpateid}">${template_opt.htbtnlibrary}</a>`;
        var htbtnMarkuppage = `<a href="#" class="htimpbtn htdisabled" data-templateid="${template_opt.templpateid}">${template_opt.htbtnpage}</a>`;

        // Enter page title then enable button
        $('#htpagetitle').on('input', function () {
            if( !$('#htpagetitle').val() == '' ){
                $(".htimport-button-dynamic-page .htimpbtn").removeClass('htdisabled');
            } else {
                $(".htimport-button-dynamic-page .htimpbtn").addClass('htdisabled');
            }
        });
        
        // button Dynamic content
        $( ".htimport-button-dynamic" ).html( htbtnMarkuplibrary );
        $( ".htimport-button-dynamic-page" ).html( htbtnMarkuppage );
        $( ".ui-dialog-title" ).html( template_opt.templpattitle );

        // call dialog
        $( "#httemplate-popup-area" ).dialog({
            modal: true,
            minWidth: 500,
            minHeight:300,
            buttons: {
                Close: function() {
                  $( this ).dialog( "close" );
                }
            }
        });


    });
    // Import data request
    jQuery('body').on('click', 'a.htimpbtn', function(e) {
        e.preventDefault();

        var $this = $(this),
            pagetitle = ( $('#htpagetitle').val() ) ? ( $('#htpagetitle').val() ) : '',
            templpateid = $this.data('templateid');
        $.ajax({
            url: ajaxurl,
            data: {
                'action': 'htbuilder_ajax_request',
                'httemplateid' : templpateid,
                'pagetitle' : pagetitle,
                'nonce' : HTTM.nonce
            },
            dataType: 'JSON',
            beforeSend: function(){
                $(".htspinner").addClass('loading');
                $(".htpopupcontent").hide();
            },
            success:function(data) {
                $(".htmessage").show();
                var tmediturl = HTTM.adminURL+"/post.php?post="+ data.id +"&action=elementor";
                $('.httemplate-edit').html('<a href="'+ tmediturl +'" target="_blank">'+ data.edittxt +'</a>');
            },
            complete:function(data){
                $(".htspinner").removeClass('loading');
                $(".htmessage").css( "display","block" );
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });

    });
    
});