const isScrolledIntoView = (elem) => {
    let docViewTop = $(window).scrollTop();
    let docViewBottom = docViewTop + $(window).height();

    let elemTop = $(elem).offset().top;
    let elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function Screens() {}

Screens.prototype = {
    constructor: Screens,
    view: function (element, fullyInView) {
      if(!element.length) return;
      let pageTop = $(window).scrollTop();
      let pageBottom = pageTop + $(window).height();
      let elementTop = $(element).offset().top;
      let elementBottom = elementTop + $(element).height();

      if (fullyInView === true) {
        return ((pageTop < elementTop) && (pageBottom > elementBottom));
      } else {
        return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
      }
    }
};

export const Screen = new Screens();
