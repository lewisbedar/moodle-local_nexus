(function() {
  var initDock = function() {
    var dock = document.querySelector('[data-nexus-dock]');

    if (!dock) {
      return;
    }

    var previous = document.querySelector('[data-nexus-dock-prev]');
    var next = document.querySelector('[data-nexus-dock-next]');

    if (!previous || !next) {
      return;
    }

    var getStep = function() {
      var item = dock.querySelector('.fc-dock-item');

      if (!item) {
        return dock.clientWidth;
      }

      var style = window.getComputedStyle(dock);
      var gap = parseFloat(style.columnGap || style.gap || 0);

      return (item.getBoundingClientRect().width + gap) * 5;
    };

    previous.addEventListener('click', function() {
      dock.scrollBy({
        left: -getStep(),
        behavior: 'smooth'
      });
    });

    next.addEventListener('click', function() {
      dock.scrollBy({
        left: getStep(),
        behavior: 'smooth'
      });
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDock);
  } else {
    initDock();
  }
}());
