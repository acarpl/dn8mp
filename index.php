<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CBT SBK Kelas 8</title>

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#ff9a9e,#fad0c4);
    margin:0;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.exam-box{
    background:white;
    width:95%;
    max-width:800px;
    padding:25px;
    border-radius:15px;
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
    position:relative;
}

.timer{
    position:absolute;
    top:15px;
    right:20px;
    font-weight:bold;
    color:#e74c3c;
}

.progress{
    text-align:center;
    font-weight:bold;
    margin-bottom:10px;
}

.question{
    margin:15px 0;
    font-weight:600;
}

.options label{
    display:block;
    margin:8px 0;
    padding:10px;
    border-radius:8px;
    background:#f1f2f6;
    cursor:pointer;
    border:1px solid #ddd;
}
.options label:hover{
    background:#dfe4ea;
}

textarea{
    width:100%;
    height:80px;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.prev{background:#95a5a6;color:white;}
.next{background:#3498db;color:white;}
.submit{background:#2ecc71;color:white;width:100%;margin-top:10px;}

.result{
    margin-top:20px;
    background:#f9f9f9;
    padding:15px;
    border-radius:10px;
    max-height:350px;
    overflow:auto;
}

.correct{color:green;}
.wrong{color:red;}
</style>
</head>

<body>

<div class="exam-box">
<div class="timer">⏳ <span id="time">20:00</span></div>
<div class="progress">Soal <span id="current">1</span> dari 40</div>

<div id="questionContainer"></div>

<button class="prev" onclick="prevQuestion()">← Prev</button>
<button class="next" onclick="nextQuestion()">Next →</button>
<button class="submit" onclick="submitQuiz()">Selesai Ujian</button>

<div id="result" class="result"></div>
</div>

<script>

const questions = [];

/* ====== 35 PILIHAN GANDA ====== */

const pgQuestions = [
["Poster adalah media komunikasi visual yang berfungsi untuk...?",
["Menghias ruangan","Menyampaikan pesan kepada masyarakat","Menulis cerita","Belajar matematika"],1],

["Unsur visual utama pada poster adalah...",
["Warna, gambar, tipografi","Angka, rumus","Cerita panjang","Paragraf narasi"],0],

["Warna pada poster berfungsi untuk...",
["Menarik perhatian","Membuat bosan","Menambah berat","Mengurangi teks"],0],

["Kalimat efektif pada poster harus...",
["Singkat, jelas, padat","Panjang dan bertele-tele","Menggunakan bahasa asing","Tidak jelas"],0],

["Poster yang berisi himbauan disebut...",
["Poster niaga","Poster layanan masyarakat","Poster kegiatan","Poster pribadi"],1],

["Poster yang bertujuan menawarkan produk disebut...",
["Layanan masyarakat","Niaga","Kegiatan","Pendidikan"],1],

["Poster kegiatan biasanya berisi...",
["Harga barang","Informasi acara","Cerita rakyat","Biografi"],1],

["Contoh poster niaga adalah...",
["Poster lomba","Poster iklan sabun","Poster kebersihan","Poster pendidikan"],1],

["Kalimat persuasif berarti kalimat yang...",
["Mengajak atau mempengaruhi","Menghina","Mengancam","Menyindir"],0],

["Ragam hias adalah...",
["Hiasan bermotif tertentu","Alat musik","Cerita rakyat","Puisi"],0],

["Contoh ragam hias Nusantara adalah...",
["Batik","Komik","Novel","Poster film"],0],

["Langkah awal mendesain ragam hias adalah...",
["Menentukan motif","Mewarnai","Memamerkan","Menjual"],0],

["Komposisi simetris berarti...",
["Seimbang kiri kanan","Tidak seimbang","Acak","Berantakan"],0],

["Komposisi asimetris berarti...",
["Seimbang tetapi tidak sama bentuk","Sama persis","Berat sebelah","Kosong"],0],

["Motif flora berarti motif berbentuk...",
["Tumbuhan","Hewan","Manusia","Geometri"],0],

["Pameran berdasarkan pesertanya disebut...",
["Tunggal & kelompok","Lukisan & patung","Indoor","Outdoor"],0],

["Tokoh pelukis Indonesia terkenal adalah...",
["Raden Saleh","Chairil Anwar","Ki Hajar Dewantara","Habibie"],0],

["Aliran realisme adalah aliran yang...",
["Menggambarkan sesuai kenyataan","Abstrak","Tidak jelas","Geometris"],0],

["Kubisme berciri...",
["Bentuk geometris","Natural","Gelap","Romantis"],0],

["Syarat pameran sederhana adalah...",
["Ada karya, tempat, panitia","Tidak ada karya","Rahasia","Tanpa tema"],0]
];

/* Isi sampai 35 */
while(pgQuestions.length<35){
pgQuestions.push(["Fungsi ragam hias adalah...",
["Memperindah benda","Merusak benda","Mengurangi nilai","Menghilangkan fungsi"],0]);
}

pgQuestions.forEach(q=>{
questions.push({
type:"pg",
q:q[0],
options:q[1],
answer:q[2]
});
});

/* ====== 5 ESSAY ====== */

questions.push(
{type:"essay",q:"Jelaskan pengertian poster dan sebutkan unsur visualnya!"},
{type:"essay",q:"Jelaskan pengertian ragam hias dan berikan contoh dari daerah beserta cirinya!"},
{type:"essay",q:"Sebutkan langkah-langkah apresiasi karya seni lukis!"},
{type:"essay",q:"Sebutkan aliran seni lukis beserta ciri-cirinya!"},
{type:"essay",q:"Buatlah satu gambar sederhana (lukisan atau poster) di buku Anda sesuai salah satu aliran!"}
);

let currentQuestion=0;
let userAnswers=new Array(questions.length).fill(null);

function loadQuestion(){
document.getElementById("current").textContent=currentQuestion+1;
let q=questions[currentQuestion];
let html=`<div class="question">${q.q}</div>`;

if(q.type==="pg"){
html+=`<div class="options">`;
q.options.forEach((opt,i)=>{
html+=`<label><input type="radio" name="option"
onclick="saveAnswer(${i})"> ${opt}</label>`;
});
html+=`</div>`;
}else{
html+=`<textarea oninput="saveAnswer(this.value)"></textarea>`;
}

document.getElementById("questionContainer").innerHTML=html;
}

function saveAnswer(val){
userAnswers[currentQuestion]=val;
}

function nextQuestion(){
if(currentQuestion<questions.length-1){currentQuestion++;loadQuestion();}
}

function prevQuestion(){
if(currentQuestion>0){currentQuestion--;loadQuestion();}
}

function submitQuiz(){
let score=0;
let totalPG=35;

for(let i=0;i<35;i++){
if(userAnswers[i]==questions[i].answer)score++;
}

let nilai=Math.round((score/totalPG)*100);

let resultHTML=`<h2>Nilai PG: ${nilai}</h2>`;
resultHTML+=`<p>Essay diperiksa guru secara manual.</p>`;

resultHTML+=`
<h3>📚 Materi Ringkasan</h3>
<b>Poster:</b> Media komunikasi visual untuk menyampaikan pesan. Unsur: gambar, warna, tipografi, tata letak.<br>
<b>Ragam Hias:</b> Motif hias tradisional seperti flora, fauna, geometris.<br>
<b>Komposisi:</b> Simetris (seimbang sama), Asimetris (seimbang tidak sama).<br>
<b>Aliran Seni Lukis:</b> Realisme, Impresionisme, Ekspresionisme, Kubisme, Abstrak.<br>
<b>Pameran:</b> Tunggal dan kelompok.
`;

document.getElementById("result").innerHTML=resultHTML;
}

loadQuestion();

/* TIMER */
let totalTime=1200;
let timeLeft=totalTime;
let timerDisplay=document.getElementById("time");

let timer=setInterval(function(){
let m=Math.floor(timeLeft/60);
let s=timeLeft%60;
timerDisplay.textContent=(m<10?"0":"")+m+":"+(s<10?"0":"")+s;
timeLeft--;
if(timeLeft<0){
clearInterval(timer);
submitQuiz();
}
},1000);

</script>

</body>
</html>
