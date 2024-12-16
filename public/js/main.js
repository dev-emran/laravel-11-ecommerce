/**
    * selectImages
    * menuleft
    * tabs
    * progresslevel
    * collapse_menu
    * fullcheckbox
    * showpass
    * gallery
    * coppy
    * select_colors_theme
    * icon_function
    * box_search
    * preloader
*/

; (function ($) {

  "use strict";

  var selectImages = function () {
    if ($(".image-select").length > 0) {
      const selectIMG = $(".image-select");
      selectIMG.find("option").each((idx, elem) => {
        const selectOption = $(elem);
        const imgURL = selectOption.attr("data-thumbnail");
        if (imgURL) {
          selectOption.attr(
            "data-content",
            "<img src='%i'/> %s"
              .replace(/%i/, imgURL)
              .replace(/%s/, selectOption.text())
          );
        }
      });
      selectIMG.selectpicker();
    }
  };

  var menuleft = function () {
    if ($('div').hasClass('section-menu-left')) {
      var bt = $(".section-menu-left").find(".has-children");
      bt.on("click", function () {
        var args = { duration: 200 };
        if ($(this).hasClass("active")) {
          $(this).children(".sub-menu").slideUp(args);
          $(this).removeClass("active");
        } else {
          $(".sub-menu").slideUp(args);
          $(this).children(".sub-menu").slideDown(args);
          $(".menu-item.has-children").removeClass("active");
          $(this).addClass("active");
        }
      });
      $('.sub-menu-item').on('click', function (event) {
        event.stopPropagation();
      });
    }
  };

  var tabs = function () {
    $('.widget-tabs').each(function () {
      $(this).find('.widget-content-tab').children().hide();
      $(this).find('.widget-content-tab').children(".active").show();
      $(this).find('.widget-menu-tab').find('li').on('click', function () {
        var liActive = $(this).index();
        var contentActive = $(this).siblings().removeClass('active').parents('.widget-tabs').find('.widget-content-tab').children().eq(liActive);
        contentActive.addClass('active').fadeIn("slow");
        contentActive.siblings().removeClass('active');
        $(this).addClass('active').parents('.widget-tabs').find('.widget-content-tab').children().eq(liActive).siblings().hide();
      });
    });
  };

  $('ul.dropdown-menu.has-content').on('click', function (event) {
    event.stopPropagation();
  });
  $('.button-close-dropdown').on('click', function () {
    $(this).closest('.dropdown').find('.dropdown-toggle').removeClass('show');
    $(this).closest('.dropdown').find('.dropdown-menu').removeClass('show');
  });

  var progresslevel = function () {
    if ($('div').hasClass('progress-level-bar')) {
      var bars = document.querySelectorAll('.progress-level-bar > span');
      setInterval(function () {
        bars.forEach(function (bar) {
          var t1 = parseFloat(bar.dataset.progress);
          var t2 = parseFloat(bar.dataset.max);
          var getWidth = (t1 / t2) * 100;
          bar.style.width = getWidth + '%';
        });
      }, 500);
    }
  }

  var collapse_menu = function () {
    $(".button-show-hide").on("click", function () {
      $('.layout-wrap').toggleClass('full-width');
    })
  }

  var fullcheckbox = function () {
    $('.total-checkbox').on('click', function () {
      if ($(this).is(':checked')) {
        $(this).closest('.wrap-checkbox').find('.checkbox-item').prop('checked', true);
      } else {
        $(this).closest('.wrap-checkbox').find('.checkbox-item').prop('checked', false);
      }
    });
  };

  var showpass = function () {
    $(".show-pass").on("click", function () {
      $(this).toggleClass("active");
      var input = $(this).parents(".password").find(".password-input");

      if (input.attr("type") === "password") {
        input.attr("type", "text");
      } else if (input.attr("type") === "text") {
        input.attr("type", "password");
      }
    });
  }

  var gallery = function () {
    $(".button-list-style").on("click", function () {
      $(".wrap-gallery-item").addClass("list");
    });
    $(".button-grid-style").on("click", function () {
      $(".wrap-gallery-item").removeClass("list");
    });
  }

  var coppy = function () {
    $(".button-coppy").on("click", function () {
      myFunction()
    });
    function myFunction() {
      var copyText = document.getElementsByClassName("coppy-content");
      navigator.clipboard.writeText(copyText.text);
    }
  }

  var select_colors_theme = function () {
    if ($('div').hasClass("select-colors-theme")) {
      $(".select-colors-theme .item").on("click", function (e) {
        $(this).parents(".select-colors-theme").find(".active").removeClass("active");
        $(this).toggleClass("active");
      })
    }
  }

  var icon_function = function () {
    if ($('div').hasClass("list-icon-function")) {
      $(".list-icon-function .trash").on("click", function (e) {
        $(this).parents(".product-item").remove();
        $(this).parents(".attribute-item").remove();
        $(this).parents(".countries-item").remove();
        $(this).parents(".user-item").remove();
        $(this).parents(".roles-item").remove();
      })
    }
  }

  var box_search = function () {

    $(document).on('click', function (e) {
      var clickID = e.target.id; if ((clickID !== 's')) {
        $('.box-content-search').removeClass('active');
      }
    });
    $(document).on('click', function (e) {
      var clickID = e.target.class; if ((clickID !== 'a111')) {
        $('.show-search').removeClass('active');
      }
    });

    $('.show-search').on('click', function (event) {
      event.stopPropagation();
    }
    );
    $('.search-form').on('click', function (event) {
      event.stopPropagation();
    }
    );
    var input = $('.header-dashboard').find('.form-search').find('input');
    input.on('input', function () {
      if ($(this).val().trim() !== '') {
        $('.box-content-search').addClass('active');
      } else {
        $('.box-content-search').removeClass('active');
      }
    });

  }

  var retinaLogos = function () {
    var retina = window.devicePixelRatio > 1 ? true : false;
    if (retina) {
      if ($(".dark-theme").length > 0) {
        $('#logo_header').attr({ src: 'images/logo/logo.png', width: '154px', height: '52px' });
      } else {
        $('#logo_header').attr({ src: 'images/logo/logo.png', width: '154px', height: '52px' });
      }
    }
  };

  var preloader = function () {
    setTimeout(function () {
      $("#preload").fadeOut("slow", function () {
        $(this).remove();
      });
    }, 1000);
  };


  // Dom Ready
  $(function () {
    selectImages();
    menuleft();
    tabs();
    progresslevel();
    collapse_menu();
    fullcheckbox();
    showpass();
    gallery();
    coppy();
    select_colors_theme();
    icon_function();
    box_search();
    retinaLogos();
    preloader();

  });

  $(document).ready(function () {

    //Drag and drop upload
    var uploadFile = $('#upload-file');
    var fileInput = $('#myFile');
    var previewContainer = $('#imgpreview');
    var imagePreview = $('#imgView');

    // Highlight on drag enter
    uploadFile.on('dragenter', function (e) {
      e.preventDefault();
      e.stopPropagation();
      uploadFile.addClass('dragging');
    });

    // Remove highlight on drag leave
    uploadFile.on('dragleave', function (e) {
      e.preventDefault();
      e.stopPropagation();
      uploadFile.removeClass('dragging');
    });

    // Highlight on drag over
    uploadFile.on('dragover', function (e) {
      e.preventDefault();
      e.stopPropagation();
      uploadFile.addClass('dragging');
    });

    // Handle file drop
    uploadFile.on('drop', function (e) {
      e.preventDefault();
      e.stopPropagation();
      uploadFile.removeClass('dragging');

      var files = e.originalEvent.dataTransfer.files;
      if (files.length > 0) {
        fileInput[0].files = files;
        displayImagePreview(files[0]);
      }
    });

    // Trigger file input on clicking the label
    uploadFile.on('click', function () {
      fileInput.click();
    });

    // Handle file selection
    fileInput.on('change', function () {
      if (fileInput[0].files.length > 0) {
        displayImagePreview(fileInput[0].files[0]);
      }
    });

    // Display image preview
    function displayImagePreview(file) {
      if (file && file.type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.attr('src', e.target.result);
          previewContainer.show();
        };
        reader.readAsDataURL(file);
      }
    }



    //Brand, Category, Product slug generate
    $('#brandName, #cateName, #productName').on('blur', function () {
      var nameValue = $(this).val();
      var slug = generateSlug(nameValue);
      $('#brandSlug, #cateSlug, #productSlug').val(slug);
    });

    function generateSlug(text) {
      return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')        // Replace spaces with -
        .replace(/[^\w\-]+/g, '')    // Remove all non-word characters
        .replace(/\-\-+/g, '-')      // Replace multiple - with single -
        .replace(/^-+/, '')          // Trim - from start of text
        .replace(/-+$/, '');         // Trim - from end of text
    }

    //Brand form validation
    $('#createBrandFrom').validate({
      ignore: '.ignore',
      errorClass: 'errDiv',
      validClass: 'SuccessDiv',
      rules: {
        name: {
          required: true,
        },
        slug: {
          required: true,
        }
      },
      errorPlacement: function (error, element) {
        if (element.attr('name') === 'name') {
          error.appendTo($('#nameErrDiv'))
        } else if (element.attr('name') === 'slug') {
          error.appendTo($('#slugErrDiv'));
        }
        else {
          error.insertAfter(element);
        }
      },
      submitHandler: function (form) {
        $.LoadingOverlay("show");
        form.submit();
      }
    });

    //Category form validation
    $('.form-new-product').validate({
      ignore: '.ignore',
      errorClass: 'errDiv',
      validClass: 'SuccessDiv',
      rules: {
        name: {
          required: true,
        },
        slug: {
          required: true,
        }
      },
      errorPlacement: function (error, element) {
        if (element.attr('name') === 'name') {
          error.appendTo($('#cateNameErrDiv'))
        } else if (element.attr('name') === 'slug') {
          error.appendTo($('#cateSlugErrDiv'));
        }
        else {
          error.insertAfter(element);
        }
      },
      submitHandler: function (form) {
        $.LoadingOverlay("show");
        form.submit();
      }
    });


    //Brand edit form validation
    $('#EditBrandFrom').validate({
      ignore: '.ignore',
      errorClass: 'errDiv',
      validClass: 'SuccessDiv',
      rules: {
        name: {
          required: true,
        },
        slug: {
          required: true,
        }
      },
      errorPlacement: function (error, element) {
        if (element.attr('name') === 'name') {
          error.appendTo($('#nameErrDiv'))
        } else if (element.attr('name') === 'slug') {
          error.appendTo($('#slugErrDiv'));
        }
        else if (element.attr('name') === 'password_confirmation') {
          error.appendTo($('#ConfirmPassErrDiv'));
        }
        else {
          error.insertAfter(element);
        }
      },
      submitHandler: function (form) {
        $.LoadingOverlay("show");
        form.submit();
      }
    });
  });



  //Products form validation
  $('#ProductForm').validate({
    ignore: '.ignore',
    errorClass: 'errDiv',
    validClass: 'SuccessDiv',
    rules: {
      name: {
        required: true,
      },
      slug: {
        required: true,
      },
      category_id: {
        required: true,
      },
      brand_id: {
        required: true,
      },
      product_id: {
        required: true,
      },
      short_description: {
        required: true,
      },
      description: {
        required: true,
      },
      image: {
        required: true,
      },
      regular_price: {
        required: true,
      },
      sale_price: {
        required: true,
      },
      SKU: {
        required: true,
      },
      quantity: {
        required: true,
      },
    },
    errorPlacement: function (error, element) {
      if (element.attr('name') === 'image') {
        error.appendTo($('#imgErrDiv'))
      }
      else {
        error.insertAfter(element);
      }
    },
    submitHandler: function (form) {
      $.LoadingOverlay("show");
      form.submit();
    }
  });

})(jQuery);
