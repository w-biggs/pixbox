<?php
/**
 * The page template for the Pixbox front-end.
 * 
 * @package pixbox
 * @since 0.3.0
 */

get_header('pixbox');
wp_enqueue_style('pixbox_albums_css');
wp_enqueue_script('jquery');

// Load the root unless there's a specific ID provided
$this_album = 0;
if(!empty($_REQUEST['album'])){
  $this_album = $_REQUEST['album'];
}
?>
<div class="wrap">
  <div class="pixbox-front">
    <h1 class="pixbox-title">Albums</h1>
    <ul class="pxbx-grid">
      <span class="pxbx-nojs">JavaScript is required to load image albums.</span>
    </ul>
  </div>
</div>
<?php $ajax_nonce = wp_create_nonce('pixbox'); ?>
<script>
  jQuery(document).ready(function($){
    $('.pxbx-nojs').remove();

    const fetchAlbum = function(thisAlbum){
      const data = {
        url: '<?= admin_url('admin-ajax.php') ?>',
        data: {
          action: 'pxbx_get_items',
          nonce: '<?= $ajax_nonce ?>',
          album: thisAlbum
        }
      };
      $.post(data)
        .done(function(result){
          if(!result.success){
            console.error(result.data);
            return false;
          }
          result = result.data;
          if(result.password){
            checkPass(result.id, function(){
              renderAlbum(result);
            });
          } else {
            renderAlbum(result);
          }
        })
        .fail(function(request){
          console.error("AJAX album request failed: " + request.status + " - " + request.statusText);
        });
    }

    const checkPass = function(id, callback){
      const password = window.prompt("Enter the password for this album.");
      const passdata = {
        url: '<?= admin_url('admin-ajax.php') ?>',
        data: {
          action: 'pxbx_check_password',
          nonce: '<?= $ajax_nonce ?>',
          album: id,
          password: password
        }
      };
      if(password !== null){
        $.post(passdata)
          .done(function(result){
            if(result.data){
              callback();
            } else {
              alert("Incorrect password.");
            }
          })
          .fail(function(request){
            console.error("AJAX pass request failed: " + request.status + " - " + request.statusText);
          });
      }
    }

    const renderAlbum = function(result){
      const albums = result.albums;
      const photos = result.photos;
      $('.pixbox-title').html(result.title);
      if(result.parent !== result.id){
        let href = '?';
        if(result.parent > 0){
          href += 'album=' + result.parent;
        }
        if($('.pxbx-parent-link').length){
          $('.pxbx-parent-link').attr('href', href);
          $('.pxbx-parent-link').data('id', result.parent);
          $('.pxbx-parent-link').html('&lt; ' + result.parentName);
        } else {
          $('.pixbox-title').after('<a class="pxbx-parent-link" href="' +
            href + '" data-id="' + result.parent + '">&lt; ' +
            result.parentName + '</a>');
        }
      } else {
        $('.pxbx-parent-link').remove();
      }
      let html = [];
      if(albums.length === 0 && photos.length === 0){
        html = [
          '<h3 class="pxbx-no-albums"><?= __('No albums or photos found.','pixbox') ?></h3>'
        ]
      } else {
        albums.forEach(function(album){
          let href = '';
          if(!album.password){
            href = 'album=' + album.id;
          }
          html = html.concat([
            '<li class="pxbx-item pxbx-album">',
              '<a href="' + href + '" data-id="' + album.id + '" class="pxbx-item-anchor pxbx-album-anchor">',
                '<span class="pxbx-item-title">',
                  album.name,
                '</span>',
              '</a>',
            '</li>'
          ]);
        });
        photos.forEach(function(photo){
          html = html.concat([
            '<li class="pxbx-item pxbx-photo">',
              '<a href="' + photo.fullres + '" class="pxbx-item-anchor pxbx-photo-anchor">',
                '<div class="pxbx-photo-thumb-container">',
                  '<img src="' + photo.fullres + '" alt="' + photo.name + '" class="pxbx-photo-thumb">',
                '</div>',
                '<span class="pxbx-item-title pxbx-photo-title">',
                  photo.name,
                '</span>',
              '</a>',
            '</li>'
          ])
        });
      }
      $(".pxbx-grid").html(html.join("\n"));
      let url = window.location.href.split('?')[0];
      if(result.id > 0){
        url += '?album=' + result.id;
      }
      history.pushState({},'',url);
    }

    $(".pixbox-front").on('click','.pxbx-album-anchor, .pxbx-parent-link', function(e){
      e.preventDefault();
      const id = $(this).data('id');
      fetchAlbum(id)
    })

    fetchAlbum(<?= $this_album ?>);
  });
</script>
<?php
get_footer('pixbox');