(function(){

  function formatTanggal() {
    return new Date().toLocaleDateString('id-ID', {
      weekday: 'long',
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      timeZone: 'Asia/Jakarta'
    });
  }

  function formatJam() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    return `${h}:${m}:${s}`; // hanya jam
  }

  let bar = document.querySelector('.datetime-bar');
  if (!bar) {
    bar = document.createElement('div');
    bar.className = 'datetime-bar';
    document.body.appendChild(bar);
  }

  function update() {
    bar.innerHTML = `
      <div>${formatTanggal()}</div>

      <div style="margin-top:4px; font-size:19px; color:#00ff00; text-shadow:0 0 6px #00ff00;">
        ${formatJam()} WIBu
      </div>

      <div class="maker" style="
        margin-top:8px;
        font-size:13px;
        opacity:0.35;
        text-align:center;
      ">
        Create by Haniffah Khoirunnisa
      </div>
    `;
  }

  update();
  setInterval(update, 1000);

})();
