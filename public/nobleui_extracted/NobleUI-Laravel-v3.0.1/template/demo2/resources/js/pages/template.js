'use strict';

(function () {

  // Root css-variable value
  const getCssVariableValue = function(variableName) {
    let hex = getComputedStyle(document.documentElement).getPropertyValue(variableName);
    if ( hex && hex.length > 0 ) {
      hex = hex.trim();
    }
    return hex;
  }

  // Global variables
  window.config = {
    colors: {
      primary        : getCssVariableValue('--bs-primary'),
      secondary      : getCssVariableValue('--bs-secondary'),
      success        : getCssVariableValue('--bs-success'),
      info           : getCssVariableValue('--bs-info'),
      warning        : getCssVariableValue('--bs-warning'),
      danger         : getCssVariableValue('--bs-danger'),
      light          : getCssVariableValue('--bs-light'),
      dark           : getCssVariableValue('--bs-dark'),
      gridBorder     : "rgba(77, 138, 240, .15)",
    },
    fontFamily       : "'Roboto', Helvetica, sans-serif"
  }



  const body = document.body;
  const horizontalMenu = document.querySelector('.horizontal-menu');


  // Initializing bootstrap tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })



  // Initializing bootstrap popover
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })


  // Demo2: adding class 'show-submenu' to the parent .nav-item (only for mobile/tablet)
  function addShowSubmenuClass(element) {  
    // current url [Eg: profile]
    const current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');  
    
    // Get parents of the 'el' with a selector (class, id, etc..)
    function getParents(el, selector) {
      const parents = [];
      while ((el = el.parentNode) && el !== document) {
        if (!selector || el.matches(selector)) parents.push(el);
      }
      return parents;
    }

    if (current !== "") {  
      // For non-root url
      if (element.getAttribute('href').indexOf(current) !== -1) {   // Checking href of 'element' matching with current url
        if (getParents(element, '.submenu-item')) {                 // Checking if it's a submenu-item 'element' [in horizontal menu bottom-navbar - demo2] 
          if (element.closest('.nav-item.active .submenu')) {       // Checking element has a submenu
            element.closest('.nav-item.active').classList.add('show-submenu');  // adding class 'show-submenu' to the parent .nav-item (only for mobile/tablet)
          }
        }
      }
    }
  }

  if (horizontalMenu) {
    const navbarNavLinks = document.querySelectorAll('.horizontal-menu .nav li a');
    navbarNavLinks.forEach( navLink => {
      addShowSubmenuClass(navLink);
    });
  }



  // Horizontal menu in small screen devices (mobile/tablet)
  if (horizontalMenu) {
    const horizontalMenuToggleButton = document.querySelector('[data-toggle="horizontal-menu-toggle"]');
    const bottomNavbar = document.querySelector('.horizontal-menu .bottom-navbar');
    if (horizontalMenuToggleButton) {
      horizontalMenuToggleButton.addEventListener('click', function () {
        bottomNavbar.classList.toggle('header-toggled');
        horizontalMenuToggleButton.classList.toggle('open');
        body.classList.toggle('header-open'); // used for creating backdrop
      });

      // To avoid layout issues, remove body and toggler classes on window resize.
      window.addEventListener('resize', function(event) {
        bottomNavbar.classList.remove('header-toggled');
        horizontalMenuToggleButton.classList.remove('open');
        body.classList.remove('header-open');
      }, true);
    }
  }
  



  // Horizontal menu nav-item click submenu show/hide on mobile/tablet
  if (horizontalMenu) {
    const navItems = document.querySelectorAll('.horizontal-menu .page-navigation >.nav-item');
    if (window.matchMedia('(max-width: 991px)').matches) {
      navItems.forEach( function (navItem) {
        navItem.addEventListener('click', function () {
          if (!this.classList.contains('show-submenu')) {
            navItems.forEach(function (navItem) {
              navItem.classList.remove('show-submenu');
            });
          }
          this.classList.toggle('show-submenu');
        });
      });
    }
  }
    



  // Horizontal menu fixed on scroll on Demo2
  if (horizontalMenu) {
    window.addEventListener('scroll', function () {
      if (window.matchMedia('(min-width: 992px)').matches) {
        if (window.scrollY >= 60) {
          horizontalMenu.classList.add('fixed-on-scroll');
        } else {
          horizontalMenu.classList.remove('fixed-on-scroll');
        }
      }
    });
  }




  // Setup clipboard.js plugin (https://github.com/zenorocha/clipboard.js)
  const clipboardButtons = document.querySelectorAll('.btn-clipboard');

  if (clipboardButtons.length) {

    clipboardButtons.forEach( btn => {
      btn.addEventListener('mouseover', function() {
        this.innerText = 'Copy to clipboard';
      });
      btn.addEventListener('mouseout', function() {
        this.innerText = 'Copy';
      });
    });

    const clipboard = new ClipboardJS('.btn-clipboard');

    clipboard.on('success', function(e) {
      e.trigger.innerHTML = 'Copied';
      setTimeout(function() {
        e.trigger.innerHTML = 'Copy';
        e.clearSelection();
      },800)
    });
  }



  // Enable lucide icons with SVG markup
  lucide.createIcons();

})();