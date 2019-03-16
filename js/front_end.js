jQuery(document).ready(function($){
  $('.pxbx-nojs').remove();

  const fetchAlbum = function(thisAlbum){
    const data = {
      url: phpdata.ajaxurl,
      data: {
        action: 'pxbx_get_items',
        nonce: phpdata.nonce,
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
      url: phpdata.ajaxurl,
      data: {
        action: 'pxbx_check_password',
        nonce: phpdata.nonce,
        album: id,
        password: password
      }
    };
    if(password !== null){
      $.post(passdata)
        .done(function(result){
          if(result.success){
            callback();
          } else {
            alert(result.data);
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
    // remove any existing album query, and remove any leftover trailing &s or ?s
    const pageUrl = window.location.href.split('album=')[0].replace(/(?:\?|\&)+$/, "");
    if(result.parent !== result.id){
      let href = pageUrl;
      if(result.parent > 0){
        if(href.includes('?')){
          href += '&';
        } else {
          href += '?';
        }
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
        '<h3 class="pxbx-no-albums">' + phpdata.notFoundText + '</h3>'
      ]
    } else {
      albums.forEach(function(album){
        let href = pageUrl;
        if(!album.password){
          if(href.includes('?')){
            href += '&';
          } else {
            href += '?';
          }
          href += 'album=' + album.id;
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
            '<a href="' + photo.fullres + '" class="pxbx-item-anchor pxbx-photo-anchor" download>',
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
    let url = pageUrl;
    if(result.id > 0){
      if(url.includes('?')){
        url += '&';
      } else {
        url += '?';
      }
      url += 'album=' + result.id;
    }
    history.pushState({},'',url);
  }

  $(".pixbox-front").on('click','.pxbx-album-anchor, .pxbx-parent-link', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    fetchAlbum(id)
  })

  fetchAlbum(phpdata.thisAlbum);
});