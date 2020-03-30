    <script src="css_js/jquery-3.4.1.min.js"></script>
    <script src="css_js/sweetalert.min.js"></script>
    <script src="css_js/semantic.min.js"></script>

    <script>
        let link = window.location.href;
        link = link.substr(link.lastIndexOf('/')+1);
        if(link=='?i=1' || link==''){
            link='index.php';
        }

        let target = $('nav a[href="'+link+'"]');
        target.addClass('active');

    </script>

    <script>

        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function () {
                $('#preloader').remove();
            });
        });
      
        $('.ui.dropdown').dropdown();
        
        $('#src-open').on('click', function(){
            $('.main-search-field').css('top','90px');
            $('#src-open').css('display','none');
            $('#src-close').css('display','flex');
        });

        $('#src-close').on('click', function(){
            $('.main-search-field').css('top','-130px');
            $('#src-open').css('display','flex');
            $('#src-close').css('display','none');
            $('.src-inp').val('');
            $('.src-content').html('');
        });

        $('.src-inp').on('keyup', function(){
           var v = $(this).val().trim();
           if(v!=''){
               $.ajax({
                    url:'search.php',
                    method:'post',
                    data:{
                        src_value:v
                    },
                    success:function(response){
                        $('.src-content').html(response);
                    }
               });
           }
           else{
                $('.src-content').html('');
           }
        });

    </script>

    
  