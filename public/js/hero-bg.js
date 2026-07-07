/**
 * Hero background — floating particles (matches Remotion HeroComposition)
 */
(function () {
  'use strict';

  function initHeroParticles() {
    var container = document.getElementById('hero-particles');
    if (!container || container.childElementCount > 0) return;

    for (var i = 1; i <= 28; i++) {
      var seed = i;
      var x = ((seed * 137.508) % 1) * 100;
      var y = ((seed * 23.4) % 1) * 100;
      var size = 1.5 + ((seed * 31.7) % 1) * 4;
      var isGold = seed % 3 === 0;
      var speed = 0.04 + ((seed * 73.1) % 1) * 0.08;
      var delay = ((seed * 17) % 80) / 30;

      var dot = document.createElement('div');
      dot.className = 'hero-bg-particle' + (isGold ? ' hero-bg-particle--gold' : '');
      dot.style.left = x + '%';
      dot.style.top = y + '%';
      dot.style.width = size + 'px';
      dot.style.height = size + 'px';
      dot.style.animationDuration = (18 + speed * 120) + 's';
      dot.style.animationDelay = (-delay) + 's';
      container.appendChild(dot);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroParticles);
  } else {
    initHeroParticles();
  }
})();
