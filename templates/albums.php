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
$album_obj = null;
$title = "Albums";
$parent = 0;
$parent_name = "Albums";
if(!empty($_REQUEST['album']) && $_REQUEST['album'] > 0){
  $this_album = $_REQUEST['album'];
  $album_obj = get_term($this_album, 'pixbox_albums');
  $title = "Pixbox: " . $album_obj->name;
  $parent = $album_obj->parent;
  if($parent){
    $parent_name = get_term($parent, 'pixbox_albums')->name;
  }
}

$albums = get_terms(array(
  'taxonomy' => 'pixbox_albums',
  'hide_empty' => false,
  'parent' => $this_album
));

$photos = get_posts(array(
  'posts_per_page'  => -1,
  'orderby'         => 'title',
  'post_type'       => 'pixbox_photo',
  'post_status'     => 'any',
  'tax_query'       => array(
    array(
      'taxonomy'         => 'pixbox_albums',
      'terms'            => $this_album,
      'include_children' => false,
    )
  )
));

?>
<div class="wrap">
  <div class="pixbox-front">
    <h1 class="pixbox-title">Albums</h1>
    <?php if($parent !== $this_album): ?>
      <a class="pxbx-parent-link" href="?album=<?= $parent ?>" data-id="<?= $parent ?>">&lt; <?= $parent_name ?></a>
    <?php endif; ?>
    <ul class="pxbx-grid">
      <?php if(empty($albums) && empty($photos)): ?>
        <h3 class="pxbx-no-albums"><?= __('No albums found.','pixbox') ?></h3>
      <?php else: ?>
        <?php foreach ($albums as $album): ?>
          <li class="pxbx-item pxbx-album">
            <a href="?album=<?= $album->term_id ?>" data-id="<?= $album->term_id ?>" class="pxbx-item-anchor pxbx-album-anchor">
              <span class="pxbx-item-title">
                <?= $album->name ?>
              </span>
            </a>
          </li>
        <?php endforeach; ?>
        <?php foreach ($photos as $photo): ?>
          <li class="pxbx-item pxbx-photo">
            <a href="<?= get_post_meta($photo->ID, 'fullres', true) ?>" class="pxbx-item-anchor pxbx-photo-anchor">
              <div class="pxbx-photo-thumb-container">
                <img src="<?= get_post_meta($photo->ID, 'fullres', true) ?>" alt="<?= $photo->post_title ?>" class="pxbx-photo-thumb">
              </div>
              <span class="pxbx-item-title pxbx-photo-title">
                <?= $photo->post_title ?>
              </span>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</div>
<?php $ajax_nonce = wp_create_nonce('pixbox'); ?>
<script>
  jQuery(document).ready(function($){
    const fetchAlbum = function(album){
      const data = {
        url: '<?= admin_url('admin-ajax.php') ?>',
        data: {
          action: 'get_pixbox_items',
          nonce: '<?= $ajax_nonce ?>',
          album: album
        }
      };
      $.post(data)
        .done(function(result){
          result = JSON.parse(result);
          const albums = result.albums;
          const photos = result.photos;
          $('.pixbox-title').html(result.title);
          if(result.parent !== result.id){
            if($('.pxbx-parent-link').length){
              $('.pxbx-parent-link').attr('href', '?album=' + result.parent);
              $('.pxbx-parent-link').data('id', result.parent);
              $('.pxbx-parent-link').html('&lt; ' + result.parentName);
            } else {
              $('.pixbox-title').after('<a class="pxbx-parent-link" href="?album=' +
                result.parent + '" data-id="' + result.parent + '">&lt; ' +
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
              html = html.concat([
                '<li class="pxbx-item pxbx-album">',
                  '<a href="?album=' + album.id + '" data-id="' + album.id + '" class="pxbx-item-anchor pxbx-album-anchor">',
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
        })
        .fail(function(request){
          console.error("AJAX request failed: " + request.status + " - " + request.statusText);
        });
    }

    $(".pixbox-front").on('click','.pxbx-album-anchor, .pxbx-parent-link',function(e){
      e.preventDefault();
      const id = $(this).data('id');
      fetchAlbum(id);
    })
  });
</script>
<?php
get_footer('pixbox');