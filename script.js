function saveResult() {
  const result = document.getElementById('result').innerText;
  let history = JSON.parse(localStorage.getItem('age_history')) || [];
  history.unshift(result);
  localStorage.setItem('age_history', JSON.stringify(history));
  alert("Result saved to browser history ✅");
}

function shareWhatsApp() {
  const text = document.getElementById('result').innerText;
  const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
}

function downloadPDF() {
  window.print();
}
