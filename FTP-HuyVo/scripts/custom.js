jQuery(document).ready(function ($) {
  // Variation form handler
  $("form.variations_form").on("show_variation", function (event, variation) {
    console.log("show_variation");
    console.log(variation);
    updatePrice(variation);
    updateVariationText();
  });

  function updatePrice(variation) {
    console.log("updatePrice");
    console.log(variation);
    const price = variation.price_html;
    $(".summary.entry-summary .price").html(price);
  }

  // Shop Filter Handler - Chỉ xử lý filter và render lại products
  if ($(".shop-filters").length) {
    initShopFilterHandler();
  }

  function initShopFilterHandler() {
    let currentUrl = window.location.href;

    // Listen for URL changes to filter and render products
    function checkUrlChange() {
      if (window.location.href !== currentUrl) {
        currentUrl = window.location.href;
        filterAndRenderProducts();
      }
    }

    // Check URL changes periodically
    setInterval(checkUrlChange, 100);

    // Also listen for popstate (browser back/forward)
    $(window).on("popstate", function () {
      currentUrl = window.location.href;
      filterAndRenderProducts();
    });
  }

  function filterAndRenderProducts() {
    const $productsContainer = $(".products-wrapper");
    if (!$productsContainer.length) {
      return;
    }

    // Prevent multiple simultaneous requests
    if ($productsContainer.hasClass("loading")) {
      return;
    }

    // Show loading state
    $productsContainer.addClass("loading");

    // Get current URL params
    const urlParams = new URLSearchParams(window.location.search);
    const filterData = {
      action: "filter_shop_products",
      filters: {},
    };

    // Collect all filter params (skip query type params)
    urlParams.forEach((value, key) => {
      if (
        key.indexOf("_query_type") === -1 &&
        key !== "paged" &&
        key !== "orderby"
      ) {
        filterData.filters[key] = value;
      }
    });

    // Add current page info
    filterData.page = urlParams.get("paged") || 1;
    filterData.orderby = urlParams.get("orderby") || "";

    // AJAX request to filter products
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: filterData,
      success: function (response) {
        if (response.success && response.data) {
          // Replace products container content
          $productsContainer.html(response.data.products);

          // Update Load More button after filter (reset to page 1)
          if (response.data.max_pages) {
            const $button = $(".load-more-products");
            if ($button.length) {
              $button.data("page", 1);
              $button.data("max-pages", response.data.max_pages);
              if (response.data.max_pages > 1) {
                $button.closest(".load-more-wrapper").show();
              } else {
                $button.closest(".load-more-wrapper").hide();
              }
            } else if (response.data.max_pages > 1) {
              // Create button if it doesn't exist
              const $wrapper = $(".products-wrapper");
              if ($wrapper.length) {
                $wrapper.append(
                  '<div class="btn-action load-more-wrapper">' +
                    '<button class="btn-primary-1 load-more-products" data-page="1" data-max-pages="' +
                    response.data.max_pages +
                    '">View More</button>' +
                    "</div>"
                );
              }
            }
          }

          // Trigger WooCommerce events
          $(document.body).trigger("wc_update_product_list");
          if (typeof window.FE.lozad !== "undefined") {
            window.FE.lozad();
          }
        }
      },
      error: function () {
        console.error("Error filtering products");
      },
      complete: function () {
        $productsContainer.removeClass("loading");
      },
    });
  }

  // Load More Products Handler
  $(document).on("click", ".load-more-products", function (e) {
    e.preventDefault();

    const $button = $(this);
    const $productsContainer = $(".products");

    if (!$productsContainer.length || $button.hasClass("loading")) {
      return;
    }

    // Disable button and show loading
    $button.addClass("loading").prop("disabled", true);
    const currentPage = parseInt($button.data("page")) || 1;

    // Get current URL params for filters
    const urlParams = new URLSearchParams(window.location.search);
    const filterData = {
      action: "load_more_products",
      page: currentPage,
      filters: {},
    };

    // Collect all filter params
    urlParams.forEach((value, key) => {
      if (
        key.indexOf("_query_type") === -1 &&
        key !== "paged" &&
        key !== "orderby"
      ) {
        filterData.filters[key] = value;
      }
    });

    filterData.orderby = urlParams.get("orderby") || "";

    // AJAX request to load more products
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: filterData,
      success: function (response) {
        if (response.success && response.data) {
          // Append new products to container
          $productsContainer.append(response.data.products);

          // Update button data and visibility
          if (response.data.has_more) {
            $button.data("page", response.data.next_page);
            $button.data("max-pages", response.data.max_pages);
            $button.removeClass("loading").prop("disabled", false);
          } else {
            // No more products, hide button
            $button.closest(".load-more-wrapper").fadeOut();
          }

          // Trigger WooCommerce events
          $(document.body).trigger("wc_update_product_list");
          if (typeof window.FE.lozad !== "undefined") {
            window.FE.lozad();
          }
        }
      },
      error: function () {
        console.error("Error loading more products");
        $button.removeClass("loading").prop("disabled", false);
      },
    });
  });

  //Single Product
  class AddToCartHandler {
    constructor() {
      this.initEventListeners();
    }

    initEventListeners() {
      $(document).on("submit", "form.cart", (e) => this.handleSubmit(e));
    }

    handleSubmit(e) {
      e.preventDefault();
      const $form = $(e.currentTarget);
      const button = $form.find(".btn-add-cart");
      const productId = $form.find('input[name="product_id"]').val();
      const variationId = $form.find('input[name="variation_id"]').val() || 0;
      const quantity = $form.find('input[name="quantity"]').val() || 1;

      const $product_id = variationId ? variationId : productId;
      let data = {
        action: "woocommerce_add_to_cart",
        product_id: $product_id,
        quantity: quantity,
      };

      // if (variationId && variationId !== '0') {
      //     data.variation_id = variationId;
      //     $form.find('select[name^="attribute_"]').each(function() {
      //         data[$(this).attr('name')] = $(this).val();
      //     });
      // }
      const $miniCart = $(".mini-cart-wrapper");

      $.ajax({
        url: wc_add_to_cart_params.ajax_url,
        type: "POST",
        data: data,
        beforeSend: function () {
          button.prop("disabled", true);
          button
            .find(".icon")
            .html('<i class="fa-solid fa-spinner-third fa-spin"></i>'); // ThĂªm spinner
        },
        success: function (res) {
          update_cart(res);
        },
        complete: function () {
          button.prop("disabled", false); // Báº­t láº¡i nĂºt
          setTimeout(() => {
            button
              .find(".icon")
              .html('<i class="fa-regular fa-cart-circle-check"></i>');
          }, 1000);
          setTimeout(() => {
            button
              .find(".icon")
              .html('<i class="fa-regular fa-cart-flatbed-boxes"></i>');
          }, 2000);
          $miniCart.addClass("active");
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", status, error);
          $form
            .find(".single_add_to_cart_button")
            .html('<i class="fal fa-cart-plus"></i>')
            .prop("disabled", false);
        },
      });
    }
  }
  function update_cart(res) {
    $(document.body).trigger("wc_fragment_refresh");

    const $cartCount = $("span.cart-count");
    const $miniCart = $(".mini-cart-wrapper");
    $miniCart
      .find(".content")
      .html(res.fragments["div.widget_shopping_cart_content"]);
    $cartCount.each(function () {
      var $this = $(this);

      $this.html(res.fragments["span.cart-count"]);
    });
  }
  new AddToCartHandler();

  // Toggle mini cart
  $(document).on("click", ".cart-btn", function (e) {
    e.preventDefault();
    e.stopPropagation();

    $(".mini-cart-wrapper").toggleClass("active");
  });

  $(document).on("click", ".mini-cart-close", function (e) {
    e.preventDefault();
    $(".mini-cart-wrapper").removeClass("active");
  });

  $(document).on("click", function (e) {
    if (
      !$(e.target).closest(".mini-cart-wrapper").length &&
      !$(e.target).closest(".cart-btn").length
    ) {
      $(".mini-cart-wrapper").removeClass("active");
    }
  });

  $(document).on("click", ".mini-cart-wrapper", function (e) {
    e.stopPropagation();
  });

  $(document.body).on("click", ".quantity button", function (e) {
    e.preventDefault();
    const button = $(this);
    const input = button.closest(".quantity-input-wrapper").find("input");
    let value = parseInt(input.val());
    if (button.hasClass("minus") && value > 1) {
      value--;
      input.val(value);
      input.trigger("change");
    } else if (button.hasClass("plus")) {
      value++;
      input.val(value);
      input.trigger("change");
    }
  });
  $(document.body).on("change", ".product-quantity input.qty", function (e) {
    updateCart();
    console.log("change");
  });

  function updateCart() {
    let button = $('button[name="update_cart"]');
    button.attr("disabled", false);
    button.trigger("click");
  }
  function showMiniCartLoading() {
    if (!$(".woocommerce-mini-cart .woo-mini-cart-loading").length) {
      $(".woocommerce-mini-cart")
        .css("position", "relative")
        .append('<div class="woo-mini-cart-loading"></div>');
    }
  }

  function hideMiniCartLoading() {
    $(".woocommerce-mini-cart .woo-mini-cart-loading").remove();
  }

  $(document).on("change", ".woocommerce-mini-cart .qty", function () {
    let input = $(this);
    let name = input.attr("name");
    let match = name.match(/cart\[([^\]]+)\]\[qty\]/);

    if (!match) return;

    let cartKey = match[1];
    let qty = input.val();

    showMiniCartLoading();

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "update_mini_cart",
        cart_item_key: cartKey,
        quantity: qty,
      },
      success: function () {
        // ⚠️ BẮT BUỘC PHẢI GỌI
        $(document.body).trigger("wc_fragment_refresh");
      },
    });
  });

  // Woo refresh xong fragment → xoá loading
  $(document.body).on(
    "wc_fragments_refreshed wc_fragments_loaded",
    function () {
      hideMiniCartLoading();
    }
  );

  $(".section-home-popular .btn-view-more").on("click", function (e) {
    e.preventDefault();
    $(".section-home-popular .popular-grid .popular-item").removeClass(
      "hidden"
    );
    $(".section-home-popular .btn-view-more").hide();
  });

  // Cart Policy
  $(document).on(
    "click",
    ".cart-policy-item .policy-item-header",
    function (e) {
      e.preventDefault();
      const $this = $(this);
      const $policyItem = $this.closest(".cart-policy-item");
      if ($policyItem.hasClass("active")) {
        $policyItem.removeClass("active");
      } else {
        $policyItem.addClass("active");
      }
    }
  );

  // Vietnam Address Handler
  function initVietnamAddress() {
    const $provinceSelect = $("#billing_state"); // Dùng billing_state thay vì billing_province
    const $districtSelect = $("#billing_district");
    const $wardSelect = $("#billing_ward");

    if (!$provinceSelect.length) {
      return; // Not on checkout page
    }

    // Sử dụng text từ PHP để có thể dịch được
    const textProvince =
      typeof text_checkout !== "undefined" && text_checkout.select_province
        ? text_checkout.select_province
        : "Chọn Tỉnh / Thành";
    const textDistrict =
      typeof text_checkout !== "undefined" && text_checkout.select_district
        ? text_checkout.select_district
        : "Chọn Quận / Huyện";
    const textWard =
      typeof text_checkout !== "undefined" && text_checkout.select_ward
        ? text_checkout.select_ward
        : "Chọn Phường / Xã";

    // Initialize Select2 for all select fields if not already initialized

    setTimeout(function () {
      if (
        $provinceSelect.length &&
        !$provinceSelect.hasClass("select2-hidden-accessible")
      ) {
        $provinceSelect.select2({
          placeholder: textProvince,
          allowClear: true,
          width: "100%",
        });
      }

      if (
        $districtSelect.length &&
        !$districtSelect.hasClass("select2-hidden-accessible")
      ) {
        $districtSelect.select2({
          placeholder: textDistrict,
          allowClear: true,
          width: "100%",
        });
      }

      if (
        $wardSelect.length &&
        !$wardSelect.hasClass("select2-hidden-accessible")
      ) {
        $wardSelect.select2({
          placeholder: textWard,
          allowClear: true,
          width: "100%",
        });
      }
    }, 100);

    // Lưu giá trị hiện tại trước khi load
    // Lấy từ select element (WooCommerce đã populate giá trị vào select khi load trang)
    // Nếu Select2 đã khởi tạo, có thể lấy từ Select2 hoặc từ select element
    const selectedProvince =
      $provinceSelect.val() ||
      ($provinceSelect.hasClass("select2-hidden-accessible")
        ? $provinceSelect.select2("val")
        : "") ||
      "";
    const selectedDistrict =
      $districtSelect.val() ||
      ($districtSelect.hasClass("select2-hidden-accessible")
        ? $districtSelect.select2("val")
        : "") ||
      "";
    const selectedWard =
      $wardSelect.val() ||
      ($wardSelect.hasClass("select2-hidden-accessible")
        ? $wardSelect.select2("val")
        : "") ||
      "";

    // Nếu có tỉnh thành, load quận huyện
    if (selectedProvince) {
      // Kiểm tra nếu chưa có options hoặc chỉ có option mặc định hoặc option đã chọn không tồn tại
      const districtOptionsCount = $districtSelect.find("option").length;
      const hasDistrictOption = selectedDistrict
        ? $districtSelect.find(`option[value="${selectedDistrict}"]`).length > 0
        : false;

      if (
        districtOptionsCount <= 1 ||
        (selectedDistrict && !hasDistrictOption)
      ) {
        // Lưu giá trị district và ward trước khi load để restore sau
        const savedDistrict = selectedDistrict;
        const savedWard = selectedWard;

        loadDistricts(selectedProvince, savedDistrict, function () {
          // Sau khi load quận huyện xong, nếu có giá trị quận huyện thì load phường xã
          if (savedDistrict) {
            const wardOptionsCount = $wardSelect.find("option").length;
            const hasWardOption = savedWard
              ? $wardSelect.find(`option[value="${savedWard}"]`).length > 0
              : false;

            if (wardOptionsCount <= 1 || (savedWard && !hasWardOption)) {
              loadWards(savedDistrict, savedWard);
            } else if (savedWard) {
              // Nếu đã có option, chỉ cần set lại value
              $wardSelect.val(savedWard).trigger("change");
            }
          }
        });
      } else {
        // Nếu đã có options, kiểm tra và load phường xã nếu cần
        if (selectedDistrict) {
          const wardOptionsCount = $wardSelect.find("option").length;
          const hasWardOption = selectedWard
            ? $wardSelect.find(`option[value="${selectedWard}"]`).length > 0
            : false;

          if (wardOptionsCount <= 1 || (selectedWard && !hasWardOption)) {
            loadWards(selectedDistrict, selectedWard);
          } else if (selectedWard) {
            // Nếu đã có option, chỉ cần set lại value
            $wardSelect.val(selectedWard).trigger("change");
          }
        }
      }
    }

    // Handle province change (billing_state)
    $provinceSelect.on("change select2:select", function () {
      const provinceCode = $(this).val();

      // Destroy and reinitialize Select2 for district
      if ($districtSelect.hasClass("select2-hidden-accessible")) {
        $districtSelect.select2("destroy");
      }
      $districtSelect
        .empty()
        .append('<option value="">' + textDistrict + "</option>");

      // Destroy and reinitialize Select2 for ward
      if ($wardSelect.hasClass("select2-hidden-accessible")) {
        $wardSelect.select2("destroy");
      }
      $wardSelect.empty().append('<option value="">' + textWard + "</option>");

      if (provinceCode) {
        loadDistricts(provinceCode, null);
      } else {
        // Reinitialize Select2 even if no province selected
        $districtSelect.select2({
          placeholder: textDistrict,
          allowClear: true,
          width: "100%",
        });
        $wardSelect.select2({
          placeholder: textWard,
          allowClear: true,
          width: "100%",
        });
      }
    });

    // Handle district change
    $districtSelect.on("change select2:select", function () {
      const districtCode = $(this).val();

      // Destroy and reinitialize Select2 for ward
      if ($wardSelect.hasClass("select2-hidden-accessible")) {
        $wardSelect.select2("destroy");
      }
      $wardSelect.empty().append('<option value="">' + textWard + "</option>");

      if (districtCode) {
        loadWards(districtCode, null);
      } else {
        // Reinitialize Select2 even if no district selected
        $wardSelect.select2({
          placeholder: textWard,
          allowClear: true,
          width: "100%",
        });
      }
    });
  }

  function loadDistricts(provinceCode, savedValue, callback) {
    const $districtSelect = $("#billing_district");
    // Lấy giá trị từ parameter hoặc từ select element
    const currentValue =
      savedValue !== undefined && savedValue !== null && savedValue !== ""
        ? savedValue
        : $districtSelect.val() ||
          ($districtSelect.hasClass("select2-hidden-accessible")
            ? $districtSelect.select2("val")
            : "") ||
          "";
    const textDistrict =
      typeof text_checkout !== "undefined" && text_checkout.select_district
        ? text_checkout.select_district
        : "Chọn Quận / Huyện";

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "get_districts",
        province: provinceCode,
      },
      success: function (response) {
        if (response.success && response.data) {
          // Destroy existing Select2 if exists
          if ($districtSelect.hasClass("select2-hidden-accessible")) {
            $districtSelect.select2("destroy");
          }

          $districtSelect.empty();
          $.each(response.data, function (value, label) {
            const $option = $("<option>", {
              value: value,
              text: label,
            });
            if (value === currentValue) {
              $option.prop("selected", true);
            }
            $districtSelect.append($option);
          });

          // Initialize Select2
          $districtSelect.select2({
            placeholder: textDistrict,
            allowClear: true,
            width: "100%",
          });

          // Set lại value và trigger change nếu có giá trị đã lưu
          if (currentValue) {
            $districtSelect.val(currentValue).trigger("change");
          }

          if (typeof callback === "function") {
            callback();
          }
        }
      },
      error: function () {
        console.error("Error loading districts");
      },
    });
  }

  function loadWards(districtCode, savedValue) {
    const $wardSelect = $("#billing_ward");
    // Lấy giá trị từ parameter hoặc từ select element
    const currentValue =
      savedValue !== undefined && savedValue !== null && savedValue !== ""
        ? savedValue
        : $wardSelect.val() ||
          ($wardSelect.hasClass("select2-hidden-accessible")
            ? $wardSelect.select2("val")
            : "") ||
          "";
    const textWard =
      typeof text_checkout !== "undefined" && text_checkout.select_ward
        ? text_checkout.select_ward
        : "Chọn Phường / Xã";

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "get_wards",
        district: districtCode,
      },
      success: function (response) {
        if (response.success && response.data) {
          // Destroy existing Select2 if exists
          if ($wardSelect.hasClass("select2-hidden-accessible")) {
            $wardSelect.select2("destroy");
          }

          $wardSelect.empty();
          $.each(response.data, function (value, label) {
            const $option = $("<option>", {
              value: value,
              text: label,
            });
            if (value === currentValue) {
              $option.prop("selected", true);
            }
            $wardSelect.append($option);
          });

          // Initialize Select2
          $wardSelect.select2({
            placeholder: textWard,
            allowClear: true,
            width: "100%",
          });

          // Set lại value và trigger change nếu có giá trị đã lưu
          if (currentValue) {
            $wardSelect.val(currentValue).trigger("change");
          }
        }
      },
      error: function () {
        console.error("Error loading wards");
      },
    });
  }

  // Initialize Vietnam address handler
  initVietnamAddress();

  // Reload address fields when checkout is updated (e.g., country change)
  $(document.body).on("update_checkout", function () {
    // Delay để đảm bảo form đã được update
    setTimeout(function () {
      const $provinceSelect = $("#billing_state"); // Dùng billing_state
      if ($provinceSelect.length && $("#billing_country").val() === "VN") {
        const selectedProvince = $provinceSelect.val();
        const selectedDistrict = $("#billing_district").val();
        const selectedWard = $("#billing_ward").val();

        // Nếu có tỉnh thành nhưng chưa có quận huyện trong dropdown
        if (selectedProvince) {
          const $districtSelect = $("#billing_district");
          const districtOptionsCount = $districtSelect.find("option").length;
          const hasDistrictOption = selectedDistrict
            ? $districtSelect.find(`option[value="${selectedDistrict}"]`)
                .length > 0
            : false;

          if (
            districtOptionsCount <= 1 ||
            (selectedDistrict && !hasDistrictOption)
          ) {
            loadDistricts(selectedProvince, selectedDistrict, function () {
              // Sau khi load quận huyện, load phường xã nếu có
              if (selectedDistrict) {
                const $wardSelect = $("#billing_ward");
                const wardOptionsCount = $wardSelect.find("option").length;
                const hasWardOption = selectedWard
                  ? $wardSelect.find(`option[value="${selectedWard}"]`).length >
                    0
                  : false;

                if (wardOptionsCount <= 1 || (selectedWard && !hasWardOption)) {
                  loadWards(selectedDistrict, selectedWard);
                } else if (selectedWard) {
                  // Nếu đã có option, chỉ cần set lại value
                  $wardSelect.val(selectedWard).trigger("change");
                }
              }
            });
          } else if (selectedDistrict) {
            // Nếu đã có quận huyện, kiểm tra phường xã
            const $wardSelect = $("#billing_ward");
            const wardOptionsCount = $wardSelect.find("option").length;
            const hasWardOption = selectedWard
              ? $wardSelect.find(`option[value="${selectedWard}"]`).length > 0
              : false;

            if (wardOptionsCount <= 1 || (selectedWard && !hasWardOption)) {
              loadWards(selectedDistrict, selectedWard);
            } else if (selectedWard) {
              // Nếu đã có option, chỉ cần set lại value
              $wardSelect.val(selectedWard).trigger("change");
            }
          }
        }

        // Re-initialize Select2 for all fields after update
        const $stateSelect = $("#billing_state");
        const $districtSelect = $("#billing_district");
        const $wardSelect = $("#billing_ward");
        const textProvince =
          typeof text_checkout !== "undefined" && text_checkout.select_province
            ? text_checkout.select_province
            : "Chọn Tỉnh / Thành";
        const textDistrict =
          typeof text_checkout !== "undefined" && text_checkout.select_district
            ? text_checkout.select_district
            : "Chọn Quận / Huyện";
        const textWard =
          typeof text_checkout !== "undefined" && text_checkout.select_ward
            ? text_checkout.select_ward
            : "Chọn Phường / Xã";

        if (
          $stateSelect.length &&
          !$stateSelect.hasClass("select2-hidden-accessible")
        ) {
          $stateSelect.select2({
            placeholder: textProvince,
            allowClear: true,
            width: "100%",
          });
        }

        if (
          $districtSelect.length &&
          !$districtSelect.hasClass("select2-hidden-accessible")
        ) {
          $districtSelect.select2({
            placeholder: textDistrict,
            allowClear: true,
            width: "100%",
          });
        }

        if (
          $wardSelect.length &&
          !$wardSelect.hasClass("select2-hidden-accessible")
        ) {
          $wardSelect.select2({
            placeholder: textWard,
            allowClear: true,
            width: "100%",
          });
        }

        // Sắp xếp lại các field theo đúng priority sau khi update
        if ($("#billing_country").val() === "VN") {
          sortCheckoutFieldsByPriority();
        }
      }
    }, 100);
  });

  // Hàm sắp xếp lại các field theo priority
  function sortCheckoutFieldsByPriority() {
    const $wrapper = $(".woocommerce-billing-fields__field-wrapper");
    if (!$wrapper.length) return;

    const $fields = $wrapper.find("p[data-priority]");

    // Detach tất cả fields để giữ lại event handlers
    const detachedFields = $fields.detach();

    // Sắp xếp theo data-priority
    const sortedFields = detachedFields.sort(function (a, b) {
      const priorityA = parseInt($(a).attr("data-priority")) || 999;
      const priorityB = parseInt($(b).attr("data-priority")) || 999;
      return priorityA - priorityB;
    });

    // Append lại theo thứ tự đã sắp xếp
    $wrapper.append(sortedFields);
  }

  // $(document).on("change", ".woo-variation-raw-select", function (e) {
  //   updateVariationText($(this));
  // });
  function updateVariationText() {
    var selects = $(".woo-variation-raw-select");
    selects.each(function () {
      var select = $(this);
      var text = select.find("option:selected").text();
      var tr = select.closest("tr");
      if (tr.find("label span").length) {
        tr.find("label span").html(" : " + text);
      } else {
        tr.find("label").append(
          '<span class="variation-text"> : ' + text + "</span>"
        );
      }
    });
  }

  function toggleVNFields() {
    const country = $("#billing_country").val();

    if (country === "VN") {
      // Hiển thị các field VN (billing_state_field giờ là tỉnh thành)
      $(
        "#billing_state_field, #billing_district_field, #billing_ward_field, #billing_address_1_field"
      ).show();
      // Ẩn các field không dùng cho VN
      $(
        "#billing_city_field, #billing_address_2_field, #billing_postcode_field"
      ).hide();

      // Đặt required cho address_1 khi VN
      $("#billing_address_1").prop("required", true);
      $("#billing_address_1_field").addClass("validate-required");

      // Bỏ required cho address_2 và postcode
      $("#billing_address_2").prop("required", false);
      $("#billing_address_2_field").removeClass("validate-required");
      $("#billing_postcode").prop("required", false);
      $("#billing_postcode_field").removeClass("validate-required");
    } else {
      // Ẩn các field VN (chỉ ẩn district và ward, giữ lại state và city)
      $("#billing_district_field, #billing_ward_field").hide();
      // Hiển thị các field quốc tế
      $(
        "#billing_state_field, #billing_city_field, #billing_address_1_field, #billing_address_2_field, #billing_postcode_field"
      ).show();

      // Khôi phục required mặc định (WooCommerce sẽ tự xử lý)
      $("#billing_address_1").prop("required", true);
      $("#billing_address_1_field").addClass("validate-required");
    }
  }

  // chạy lần đầu
  toggleVNFields();

  // Sắp xếp lại các field theo priority khi trang load
  setTimeout(function () {
    if ($("#billing_country").val() === "VN") {
      sortCheckoutFieldsByPriority();
    }
  }, 200);

  // lắng nghe thay đổi quốc gia
  $(document).on("change", "#billing_country", toggleVNFields);
  $(document).on("select2:select", "#billing_country", toggleVNFields);
  $(document.body).on("country_to_state_changing", toggleVNFields);

  // Lắng nghe khi WooCommerce update checkout fields
  $(document.body).on("update_checkout", function () {
    setTimeout(function () {
      toggleVNFields();
      if ($("#billing_country").val() === "VN") {
        sortCheckoutFieldsByPriority();
      }
    }, 100);
  });
});
