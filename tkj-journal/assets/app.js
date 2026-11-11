const dlg=document.getElementById('dlg'),det=document.getElementById('det');
function formJurnal(j){const f=dlg.querySelector('form');f.reset();
  f.jid.value=j?j.id:'';f.tanggal.value=j?j.tanggal:new Date().toLocaleDateString('sv-SE');
  f.judul.value=j?j.judul:'';f.uraian.value=j&&j.uraian?j.uraian:'';
  dlg.querySelector('h3').textContent=j?'Ubah jurnal':'Isi jurnal hari ini';
  dlg.querySelector('.go').textContent=j?'Simpan perubahan':'Kirim jurnal';dlg.showModal()}
document.querySelectorAll('#open,.open').forEach(b=>b.onclick=()=>formJurnal(null));
document.querySelectorAll('.edit').forEach(b=>b.onclick=()=>formJurnal(JSON.parse(b.dataset.j)));
document.querySelectorAll('.eye[data-j]:not(.edit)').forEach(b=>b.onclick=()=>{const j=JSON.parse(b.dataset.j);
  det.querySelector('h3').textContent=j.judul;det.querySelector('small').textContent=j.tgl;
  det.querySelector('p').textContent=j.uraian||'Tidak ada uraian.';det.showModal()});
document.querySelectorAll('[data-tutup]').forEach(b=>b.onclick=()=>b.closest('dialog').close());
document.querySelectorAll('[data-tema]').forEach(b=>{let t='auto';try{t=localStorage.getItem('tema')||'auto'}catch(e){}
  if(b.dataset.tema===t)b.classList.add('on');
  b.onclick=()=>{const v=b.dataset.tema;try{v==='auto'?localStorage.removeItem('tema'):localStorage.setItem('tema',v)}catch(e){}
    v==='auto'?delete document.documentElement.dataset.theme:document.documentElement.dataset.theme=v;
    document.querySelectorAll('[data-tema]').forEach(x=>x.classList.toggle('on',x===b))}});
