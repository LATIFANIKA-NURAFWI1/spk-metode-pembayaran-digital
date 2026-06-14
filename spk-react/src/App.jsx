import { useState, useMemo, useRef } from 'react'
import {
  ChevronDown, Star, Trophy, Shield, Zap, DollarSign,
  BarChart2, Calculator, Table2, CheckCircle2, ArrowDown,
  Sparkles, TrendingUp, Award, Info
} from 'lucide-react'
import './index.css'

/* ─── DEFAULT DATA ─── */
const DEFAULT_CRITERIA = [
  { id: 'C1', name: 'Biaya Transaksi',    type: 'Cost',    weight: 40 },
  { id: 'C2', name: 'Kemudahan',          type: 'Benefit', weight: 27 },
  { id: 'C3', name: 'Keamanan',           type: 'Benefit', weight: 24 },
  { id: 'C4', name: 'Kecepatan',          type: 'Benefit', weight:  6 },
  { id: 'C5', name: 'Popularitas',        type: 'Benefit', weight:  4 },
]

const DEFAULT_ALTERNATIVES = [
  { id: 'A1', name: 'QRIS',            values: [1.61, 4.39, 4.24, 4.24, 4.24] },
  { id: 'A2', name: 'E-Wallet',        values: [2.25, 3.88, 3.64, 3.91, 3.91] },
  { id: 'A3', name: 'Transfer Bank',   values: [3.36, 3.59, 4.12, 3.65, 3.65] },
  { id: 'A4', name: 'Kartu Debit/Kredit', values: [1.63, 3.53, 4.06, 3.88, 3.88] },
]

/* ─── TOPSIS ENGINE ─── */
function computeTopsis(alternatives, criteria) {
  const n = alternatives.length
  const m = criteria.length

  // Normalize weights
  const totalW = criteria.reduce((s, c) => s + Number(c.weight), 0)
  const W = criteria.map(c => Number(c.weight) / (totalW || 1))

  // Step 1: Normalization matrix R
  const denom = Array.from({ length: m }, (_, j) => {
    const sq = alternatives.reduce((s, a) => s + Math.pow(Number(a.values[j]), 2), 0)
    return Math.sqrt(sq) || 1
  })
  const R = alternatives.map(a => a.values.map((v, j) => Number(v) / denom[j]))

  // Step 2: Weighted normalized Y
  const Y = R.map(row => row.map((r, j) => r * W[j]))

  // Step 3: Ideal A+ and A-
  const Aplus  = Array.from({ length: m }, (_, j) => {
    const col = Y.map(row => row[j])
    return criteria[j].type === 'Benefit' ? Math.max(...col) : Math.min(...col)
  })
  const Aminus = Array.from({ length: m }, (_, j) => {
    const col = Y.map(row => row[j])
    return criteria[j].type === 'Benefit' ? Math.min(...col) : Math.max(...col)
  })

  // Step 4: Distances D+ and D-
  const Dplus  = Y.map(row => Math.sqrt(row.reduce((s, y, j) => s + Math.pow(y - Aplus[j],  2), 0)))
  const Dminus = Y.map(row => Math.sqrt(row.reduce((s, y, j) => s + Math.pow(y - Aminus[j], 2), 0)))

  // Step 5: Preference value Vi
  const Vi = alternatives.map((a, i) => {
    const denom = Dplus[i] + Dminus[i]
    return { ...a, Vi: denom === 0 ? 0 : Dminus[i] / denom }
  })

  const ranked = [...Vi].sort((a, b) => b.Vi - a.Vi).map((a, i) => ({ ...a, rank: i + 1 }))

  return { R, Y, Aplus, Aminus, Dplus, Dminus, Vi, ranked, W }
}

/* ─── HELPER: Format number ─── */
const f = (v, d = 4) => Number(v).toFixed(d)

/* ─── COMPONENTS ─── */

// ──────── NAVBAR ────────
function Navbar({ onScrollTo }) {
  return (
    <nav className="fixed top-0 left-0 right-0 z-50 px-4 py-3">
      <div className="max-w-6xl mx-auto flex items-center justify-between bg-white/70 backdrop-blur-xl border border-white/80 rounded-2xl px-5 py-3 shadow-lg shadow-purple-100/40">
        <div className="flex items-center gap-2">
          <div className="w-9 h-9 bg-gradient-to-br from-violet-400 to-pink-400 rounded-xl flex items-center justify-center shadow-md">
            <BarChart2 className="w-5 h-5 text-white" />
          </div>
          <span className="font-black text-slate-800 text-lg">SPK <span className="text-violet-500">UMKM</span></span>
        </div>
        <div className="hidden md:flex items-center gap-1">
          {[['Beranda','hero'],['Fitur','fitur'],['Data','data'],['Kalkulator','kalkulator'],['Hasil','hasil']].map(([label, id]) => (
            <button key={id} onClick={() => onScrollTo(id)}
              className="px-4 py-2 rounded-xl text-sm font-700 text-slate-600 hover:text-violet-600 hover:bg-violet-50 transition-all">
              {label}
            </button>
          ))}
        </div>
        <button onClick={() => onScrollTo('kalkulator')}
          className="bg-gradient-to-r from-violet-500 to-pink-500 text-white px-5 py-2.5 rounded-xl text-sm font-800 shadow-md hover:shadow-lg hover:scale-105 transition-all">
          Mulai Simulasi ✨
        </button>
      </div>
    </nav>
  )
}

// ──────── HERO SECTION ────────
function HeroSection({ onScrollTo }) {
  return (
    <section id="hero" className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background Image */}
      <img src="/bg.png" alt="bg" className="absolute inset-0 w-full h-full object-cover" />
      <div className="absolute inset-0 bg-gradient-to-b from-white/10 via-white/30 to-white/80" />

      <div className="relative z-10 text-center px-4 py-32 max-w-4xl mx-auto">
        <div className="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-white px-4 py-2 rounded-full text-sm font-700 text-violet-700 mb-8 shadow-md">
          <Sparkles className="w-4 h-4 text-yellow-500" />
          Sistem Pendukung Keputusan · Metode TOPSIS
        </div>

        <h1 className="text-5xl md:text-7xl font-black text-slate-800 mb-6 leading-tight">
          Pilih Pembayaran<br />
          <span className="shimmer-text">Digital Terbaik</span><br />
          untuk UMKM-mu! 🚀
        </h1>

        <p className="text-lg md:text-xl text-slate-600 mb-12 max-w-2xl mx-auto leading-relaxed font-600">
          Gunakan metode <strong>TOPSIS</strong> untuk menganalisis dan menemukan metode pembayaran digital
          paling ideal berdasarkan biaya, kemudahan, keamanan, kecepatan, dan popularitas.
        </p>

        <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
          <button onClick={() => onScrollTo('kalkulator')}
            className="group bg-gradient-to-r from-violet-500 to-pink-500 text-white px-10 py-4 rounded-2xl text-lg font-800 shadow-xl hover:shadow-2xl hover:scale-105 transition-all flex items-center gap-3">
            <Calculator className="w-6 h-6" />
            Mulai Simulasi
            <ArrowDown className="w-5 h-5 group-hover:translate-y-1 transition-transform" />
          </button>
          <button onClick={() => onScrollTo('data')}
            className="bg-white/80 backdrop-blur-sm border-2 border-white text-slate-700 px-10 py-4 rounded-2xl text-lg font-700 shadow-lg hover:shadow-xl hover:scale-105 transition-all">
            Lihat Data 📊
          </button>
        </div>

        <div className="mt-20 animate-bounce">
          <ChevronDown className="w-8 h-8 text-slate-400 mx-auto" />
        </div>
      </div>
    </section>
  )
}

// ──────── FEATURE CARDS ────────
function FeatureSection() {
  const features = [
    {
      img: '/koin.png',
      color: 'clay-card-blue',
      icon: <DollarSign className="w-6 h-6 text-blue-700" />,
      iconBg: 'bg-blue-200',
      title: 'Hemat Biaya Transaksi',
      desc: 'Temukan platform pembayaran dengan komisi paling rendah untuk memaksimalkan keuntungan bisnis UMKM kamu setiap harinya.',
      tag: '💰 Cost Efficient',
    },
    {
      img: '/shield.png',
      color: 'clay-card-pink',
      icon: <Shield className="w-6 h-6 text-pink-700" />,
      iconBg: 'bg-pink-200',
      title: 'Keamanan Terjamin',
      desc: 'Analisis tingkat keamanan tiap platform berdasarkan enkripsi data, proteksi fraud, dan rekam jejak kepercayaan pengguna.',
      tag: '🛡️ Secure & Safe',
    },
    {
      img: '/roket.png',
      color: 'clay-card-yellow',
      icon: <Zap className="w-6 h-6 text-yellow-700" />,
      iconBg: 'bg-yellow-200',
      title: 'Transaksi Super Cepat',
      desc: 'Bandingkan kecepatan proses transaksi real-time agar pelanggan tidak perlu menunggu lama saat checkout di tokomu.',
      tag: '⚡ Lightning Fast',
    },
  ]

  return (
    <section id="fitur" className="py-24 px-4 bg-gradient-to-b from-white to-violet-50/50">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-16">
          <div className="inline-flex items-center gap-2 bg-violet-100 text-violet-700 px-4 py-2 rounded-full text-sm font-700 mb-4">
            <Star className="w-4 h-4" /> Mengapa SPK Ini?
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-slate-800 mb-4">
            3 Keunggulan Utama 🌟
          </h2>
          <p className="text-slate-500 text-lg max-w-xl mx-auto font-500">
            SPK ini dirancang khusus untuk membantu pelaku UMKM membuat keputusan cerdas berbasis data.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {features.map((f, i) => (
            <div key={i} className={`clay-card ${f.color} p-8 flex flex-col items-center text-center hover:scale-105 transition-all duration-300`}
              style={{ animationDelay: `${i * 0.15}s` }}>
              {/* Mascot */}
              <div className={`w-40 h-40 mb-6 ${i === 0 ? 'float-1' : i === 1 ? 'float-2' : 'float-3'}`}>
                <img src={f.img} alt={f.title} className="w-full h-full object-contain drop-shadow-2xl" />
              </div>

              <div className={`inline-flex items-center justify-center w-12 h-12 ${f.iconBg} rounded-2xl mb-4 shadow-md`}>
                {f.icon}
              </div>

              <span className="text-xs font-800 text-slate-600 bg-white/60 px-3 py-1 rounded-full mb-3 backdrop-blur-sm">{f.tag}</span>
              <h3 className="text-xl font-800 text-slate-800 mb-3">{f.title}</h3>
              <p className="text-slate-600 text-sm leading-relaxed font-500">{f.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

// ──────── DATA TABLE SECTION ────────
function DataSection({ alternatives, criteria }) {
  return (
    <section id="data" className="py-24 px-4 bg-white">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-12">
          <div className="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-700 mb-4">
            <Table2 className="w-4 h-4" /> Data Default
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-slate-800 mb-4">
            Matriks Penilaian Awal 📋
          </h2>
          <p className="text-slate-500 text-lg max-w-xl mx-auto font-500">
            Data matriks keputusan dan bobot kriteria yang digunakan sebagai nilai default dalam perhitungan TOPSIS.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Criteria & Weights */}
          <div className="clay-card clay-card-white border border-violet-100 p-6">
            <h3 className="font-800 text-slate-800 text-lg mb-5 flex items-center gap-2">
              <BarChart2 className="w-5 h-5 text-violet-500" /> Kriteria & Bobot
            </h3>
            <div className="space-y-3">
              {criteria.map((c) => (
                <div key={c.id} className="flex items-center justify-between">
                  <div>
                    <span className="font-700 text-slate-700 text-sm">{c.id}</span>
                    <span className="text-slate-500 text-sm ml-2">{c.name}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <span className={`text-xs px-2 py-0.5 rounded-full font-700 ${c.type === 'Benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                      {c.type}
                    </span>
                    <span className="font-800 text-violet-600 text-sm">{c.weight}%</span>
                  </div>
                </div>
              ))}
            </div>
            <div className="mt-4 pt-4 border-t border-slate-100 flex justify-between">
              <span className="text-sm font-700 text-slate-600">Total Bobot</span>
              <span className="font-800 text-slate-800">{criteria.reduce((s, c) => s + Number(c.weight), 0)}%</span>
            </div>
          </div>

          {/* Decision Matrix */}
          <div className="lg:col-span-2 clay-card clay-card-white border border-blue-100 p-6">
            <h3 className="font-800 text-slate-800 text-lg mb-5 flex items-center gap-2">
              <Table2 className="w-5 h-5 text-blue-500" /> Matriks Keputusan (X)
            </h3>
            <div className="table-wrap">
              <table className="w-full text-sm">
                <thead>
                  <tr>
                    <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs">Alternatif</th>
                    {criteria.map(c => (
                      <th key={c.id} className="text-center py-2 px-3 text-slate-500 font-700 text-xs">{c.id}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {alternatives.map((a, i) => (
                    <tr key={a.id} className={`${i % 2 === 0 ? 'bg-slate-50/50' : ''} hover:bg-violet-50/50 transition-colors rounded-xl`}>
                      <td className="py-3 px-3">
                        <div className="flex items-center gap-2">
                          <span className="w-7 h-7 flex items-center justify-center bg-violet-100 text-violet-700 text-xs font-800 rounded-lg">{a.id}</span>
                          <span className="font-700 text-slate-700">{a.name}</span>
                        </div>
                      </td>
                      {a.values.map((v, j) => (
                        <td key={j} className="text-center py-3 px-3 font-600 text-slate-600">{v}</td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}

// ──────── CALCULATOR SECTION ────────
function CalculatorSection({ alternatives, setAlternatives, criteria, setCriteria }) {
  const handleValueChange = (altIdx, critIdx, val) => {
    setAlternatives(prev => {
      const next = prev.map((a, ai) => ai !== altIdx ? a : {
        ...a,
        values: a.values.map((v, ci) => ci !== critIdx ? v : val)
      })
      return next
    })
  }

  const handleWeightChange = (critIdx, val) => {
    setCriteria(prev => prev.map((c, i) => i !== critIdx ? c : { ...c, weight: val }))
  }

  const totalWeight = criteria.reduce((s, c) => s + Number(c.weight), 0)
  const weightOk = Math.abs(totalWeight - 100) < 0.01

  return (
    <section id="kalkulator" className="py-24 px-4 bg-gradient-to-b from-violet-50/50 to-pink-50/50">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-12">
          <div className="inline-flex items-center gap-2 bg-pink-100 text-pink-700 px-4 py-2 rounded-full text-sm font-700 mb-4">
            <Calculator className="w-4 h-4" /> Kalkulator Simulasi
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-slate-800 mb-4">
            Atur Nilai & Bobot 🎛️
          </h2>
          <p className="text-slate-500 text-lg max-w-xl mx-auto font-500">
            Ubah nilai matriks dan bobot kriteria sesuai kebutuhan bisnis kamu. Hasil perhitungan akan berubah secara otomatis.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
          {/* Weight inputs */}
          <div className="clay-card clay-card-white border border-pink-100 p-6">
            <h3 className="font-800 text-slate-800 text-base mb-5 flex items-center gap-2">
              <BarChart2 className="w-5 h-5 text-pink-500" /> Bobot Kriteria (%)
            </h3>
            <div className="space-y-3">
              {criteria.map((c, i) => (
                <div key={c.id}>
                  <label className="flex items-center justify-between mb-1">
                    <span className="text-sm font-700 text-slate-700">{c.id}: {c.name}</span>
                    <span className={`text-xs px-2 py-0.5 rounded-full font-700 ${c.type === 'Benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                      {c.type}
                    </span>
                  </label>
                  <input
                    type="number"
                    className="clay-input"
                    value={c.weight}
                    step="0.1"
                    onChange={e => handleWeightChange(i, e.target.value)}
                  />
                </div>
              ))}
              <div className={`flex items-center justify-between mt-3 p-3 rounded-xl ${weightOk ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}`}>
                <span className="text-sm font-700 text-slate-700">Total:</span>
                <span className={`font-800 text-base ${weightOk ? 'text-green-600' : 'text-red-600'}`}>
                  {totalWeight.toFixed(1)}% {weightOk ? '✓' : '⚠'}
                </span>
              </div>
              {!weightOk && (
                <p className="text-xs text-red-500 font-600 text-center">Total bobot harus = 100%</p>
              )}
            </div>
          </div>

          {/* Matrix inputs */}
          <div className="lg:col-span-3 clay-card clay-card-white border border-violet-100 p-6">
            <h3 className="font-800 text-slate-800 text-base mb-5 flex items-center gap-2">
              <Table2 className="w-5 h-5 text-violet-500" /> Nilai Matriks Keputusan (X)
            </h3>
            <div className="table-wrap">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b border-slate-100">
                    <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs w-36">Alternatif</th>
                    {criteria.map(c => (
                      <th key={c.id} className="text-center py-2 px-2 text-slate-500 font-700 text-xs min-w-[80px]">
                        {c.id}
                        <div className="text-slate-400 font-500 normal-case">{c.name.split(' ')[0]}</div>
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {alternatives.map((a, ai) => (
                    <tr key={a.id} className={`${ai % 2 === 0 ? 'bg-slate-50/50' : ''}`}>
                      <td className="py-3 px-3">
                        <div className="flex items-center gap-2">
                          <span className="w-7 h-7 flex items-center justify-center bg-violet-100 text-violet-700 text-xs font-800 rounded-lg flex-shrink-0">{a.id}</span>
                          <span className="font-700 text-slate-700 text-xs">{a.name}</span>
                        </div>
                      </td>
                      {a.values.map((v, ci) => (
                        <td key={ci} className="py-2 px-2">
                          <input
                            type="number"
                            className="clay-input"
                            value={v}
                            step="0.01"
                            onChange={e => handleValueChange(ai, ci, e.target.value)}
                          />
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {/* Note */}
        <div className="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-2xl p-4">
          <Info className="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" />
          <p className="text-sm text-blue-700 font-600">
            <strong>Tips:</strong> Ubah nilai pada tabel di atas dan hasil perhitungan TOPSIS di bawah akan berubah secara <em>real-time</em> secara otomatis. Pastikan total bobot = 100%.
          </p>
        </div>
      </div>
    </section>
  )
}

// ──────── TOPSIS STEPS SECTION ────────
function TopsisStepsSection({ result, criteria, alternatives }) {
  const { R, Y, Aplus, Aminus, Dplus, Dminus, Vi, W } = result

  const TableCard = ({ title, icon, color, borderColor, children }) => (
    <div className={`clay-card clay-card-white border ${borderColor} p-6 mb-6`}>
      <h3 className={`font-800 text-slate-800 text-base mb-4 flex items-center gap-2 ${color}`}>
        {icon} {title}
      </h3>
      <div className="table-wrap">{children}</div>
    </div>
  )

  const MatrixTable = ({ data, label, headerRow }) => (
    <table className="w-full text-sm">
      <thead>
        <tr className="border-b border-slate-100">
          <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs w-36">Alternatif</th>
          {criteria.map(c => (
            <th key={c.id} className="text-center py-2 px-3 text-slate-500 font-700 text-xs min-w-[90px]">{c.id}</th>
          ))}
        </tr>
      </thead>
      <tbody>
        {alternatives.map((a, i) => (
          <tr key={a.id} className={i % 2 === 0 ? 'bg-slate-50/30' : ''}>
            <td className="py-2.5 px-3">
              <div className="flex items-center gap-2">
                <span className="w-6 h-6 flex items-center justify-center bg-violet-100 text-violet-700 text-xs font-800 rounded-md">{a.id}</span>
                <span className="font-600 text-slate-700 text-xs">{a.name}</span>
              </div>
            </td>
            {data[i].map((v, j) => (
              <td key={j} className="text-center py-2.5 px-3 font-600 text-slate-600 text-xs tabular-nums">{f(v)}</td>
            ))}
          </tr>
        ))}
      </tbody>
    </table>
  )

  return (
    <section id="langkah" className="py-24 px-4 bg-white">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-12">
          <div className="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-700 mb-4">
            <TrendingUp className="w-4 h-4" /> Langkah TOPSIS
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-slate-800 mb-4">
            Proses Perhitungan Live 🔬
          </h2>
          <p className="text-slate-500 text-lg max-w-xl mx-auto font-500">
            Setiap tabel di bawah menampilkan hasil perhitungan langkah demi langkah yang berubah real-time.
          </p>
        </div>

        {/* Step 1: Weights */}
        <TableCard title="Langkah 1: Bobot Ternormalisasi (W)" icon={<BarChart2 className="w-5 h-5" />}
          color="text-violet-600" borderColor="border-violet-100">
          <div className="flex flex-wrap gap-3">
            {criteria.map((c, j) => (
              <div key={c.id} className="bg-violet-50 rounded-xl px-4 py-3 text-center min-w-[100px]">
                <div className="text-xs font-700 text-violet-500 mb-1">{c.id} · {c.name}</div>
                <div className="font-800 text-violet-700 text-lg">{f(W[j])}</div>
              </div>
            ))}
          </div>
        </TableCard>

        {/* Step 2: Normalized Matrix R */}
        <TableCard title="Langkah 2: Matriks Normalisasi (R)" icon={<Table2 className="w-5 h-5" />}
          color="text-blue-600" borderColor="border-blue-100">
          <MatrixTable data={R} />
        </TableCard>

        {/* Step 3: Weighted Normalized Y */}
        <TableCard title="Langkah 3: Matriks Normalisasi Terbobot (Y)" icon={<Table2 className="w-5 h-5" />}
          color="text-pink-600" borderColor="border-pink-100">
          <MatrixTable data={Y} />
        </TableCard>

        {/* Step 4: Ideal A+ and A- */}
        <TableCard title="Langkah 4: Solusi Ideal Positif (A⁺) & Negatif (A⁻)" icon={<Star className="w-5 h-5" />}
          color="text-yellow-600" borderColor="border-yellow-100">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-slate-100">
                <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs w-24">Solusi</th>
                {criteria.map(c => (
                  <th key={c.id} className="text-center py-2 px-3 text-slate-500 font-700 text-xs min-w-[80px]">{c.id}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              <tr className="bg-green-50/50">
                <td className="py-2.5 px-3">
                  <span className="bg-green-100 text-green-700 text-xs font-800 px-2 py-1 rounded-lg">A⁺ (Max)</span>
                </td>
                {Aplus.map((v, j) => (
                  <td key={j} className="text-center py-2.5 px-3 font-700 text-green-600 text-xs tabular-nums">{f(v)}</td>
                ))}
              </tr>
              <tr className="bg-red-50/50">
                <td className="py-2.5 px-3">
                  <span className="bg-red-100 text-red-700 text-xs font-800 px-2 py-1 rounded-lg">A⁻ (Min)</span>
                </td>
                {Aminus.map((v, j) => (
                  <td key={j} className="text-center py-2.5 px-3 font-700 text-red-500 text-xs tabular-nums">{f(v)}</td>
                ))}
              </tr>
            </tbody>
          </table>
        </TableCard>

        {/* Step 5: D+ and D- */}
        <TableCard title="Langkah 5: Jarak Solusi Ideal D⁺ dan D⁻" icon={<TrendingUp className="w-5 h-5" />}
          color="text-emerald-600" borderColor="border-emerald-100">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-slate-100">
                <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs">Alternatif</th>
                <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">D⁺ (Jarak ke A⁺)</th>
                <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">D⁻ (Jarak ke A⁻)</th>
              </tr>
            </thead>
            <tbody>
              {alternatives.map((a, i) => (
                <tr key={a.id} className={i % 2 === 0 ? 'bg-slate-50/30' : ''}>
                  <td className="py-2.5 px-3">
                    <div className="flex items-center gap-2">
                      <span className="w-6 h-6 flex items-center justify-center bg-emerald-100 text-emerald-700 text-xs font-800 rounded-md">{a.id}</span>
                      <span className="font-600 text-slate-700 text-xs">{a.name}</span>
                    </div>
                  </td>
                  <td className="text-center py-2.5 px-3 font-600 text-red-500 text-sm tabular-nums">{f(Dplus[i])}</td>
                  <td className="text-center py-2.5 px-3 font-600 text-green-600 text-sm tabular-nums">{f(Dminus[i])}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </TableCard>
      </div>
    </section>
  )
}

// ──────── RESULTS SECTION ────────
function ResultsSection({ ranked }) {
  const rankColors = [
    'from-yellow-300 to-amber-300 border-yellow-400',
    'from-slate-200 to-slate-300 border-slate-400',
    'from-orange-200 to-orange-300 border-orange-400',
    'from-blue-100 to-blue-200 border-blue-300',
  ]
  const rankEmojis = ['🥇', '🥈', '🥉', '4️⃣']
  const medalBg = ['bg-yellow-400', 'bg-slate-400', 'bg-orange-400', 'bg-blue-300']

  const maxVi = ranked[0]?.Vi || 1

  return (
    <section id="hasil" className="py-24 px-4 bg-gradient-to-b from-pink-50/50 to-violet-50/50">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-16">
          <div className="inline-flex items-center gap-2 bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-700 mb-4">
            <Trophy className="w-4 h-4" /> Hasil Akhir
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-slate-800 mb-4">
            Peringkat Rekomendasi 🏆
          </h2>
          <p className="text-slate-500 text-lg max-w-xl mx-auto font-500">
            Alternatif dengan <strong>Nilai Preferensi (Vᵢ)</strong> tertinggi adalah rekomendasi terbaik.
          </p>
        </div>

        {/* Winner highlight */}
        {ranked[0] && (
          <div className="clay-card bg-gradient-to-br from-yellow-200 via-amber-100 to-yellow-300 border-2 border-yellow-400 p-8 mb-8 text-center relative overflow-hidden">
            <div className="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-400" />
            <div className="text-6xl mb-3">🏆</div>
            <div className="badge-winner mb-4 inline-block">⭐ Rekomendasi Terbaik</div>
            <h3 className="text-4xl font-black text-slate-800 mb-2">{ranked[0].name}</h3>
            <p className="text-slate-600 font-600 mb-4">
              Dengan nilai preferensi tertinggi sebesar <strong className="text-amber-700 text-xl">{f(ranked[0].Vi)}</strong>,
              metode ini paling sesuai berdasarkan seluruh kriteria yang diperhitungkan.
            </p>
            <div className="inline-flex items-center gap-2 bg-white/70 backdrop-blur-sm px-5 py-2.5 rounded-full border border-yellow-300">
              <CheckCircle2 className="w-5 h-5 text-green-600" />
              <span className="font-700 text-slate-700">Peringkat #1 dari {ranked.length} alternatif</span>
            </div>
          </div>
        )}

        {/* Full Leaderboard */}
        <div className="clay-card clay-card-white border border-slate-100 p-6">
          <h3 className="font-800 text-slate-800 text-lg mb-6 flex items-center gap-2">
            <Award className="w-5 h-5 text-violet-500" /> Tabel Peringkat Lengkap (Nilai Preferensi Vᵢ)
          </h3>
          <div className="space-y-4">
            {ranked.map((a, i) => (
              <div key={a.id} className={`slide-in rounded-2xl border-2 bg-gradient-to-r ${rankColors[i] || 'from-slate-100 to-slate-200 border-slate-300'} p-5 hover:scale-[1.01] transition-all`}
                style={{ animationDelay: `${i * 0.1}s` }}>
                <div className="flex items-center gap-5">
                  {/* Rank Badge */}
                  <div className={`w-14 h-14 flex-shrink-0 flex items-center justify-center ${medalBg[i] || 'bg-slate-300'} rounded-2xl shadow-md text-2xl`}>
                    {rankEmojis[i] || `#${a.rank}`}
                  </div>

                  {/* Name */}
                  <div className="flex-1">
                    <div className="flex items-center gap-3 mb-2">
                      <span className="font-800 text-slate-800 text-xl">{a.name}</span>
                      {i === 0 && <span className="badge-winner text-xs">🏆 Terbaik!</span>}
                    </div>

                    {/* Progress bar */}
                    <div className="w-full bg-white/60 rounded-full h-3 overflow-hidden backdrop-blur-sm">
                      <div
                        className="h-3 rounded-full bg-gradient-to-r from-violet-500 to-pink-500 transition-all duration-700 ease-out"
                        style={{ width: `${(a.Vi / maxVi) * 100}%` }}
                      />
                    </div>
                  </div>

                  {/* Score */}
                  <div className="text-right flex-shrink-0">
                    <div className="font-900 text-slate-800 text-2xl tabular-nums">{f(a.Vi, 4)}</div>
                    <div className="text-xs text-slate-500 font-600">Nilai Vᵢ</div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Summary Table */}
        <div className="clay-card clay-card-white border border-slate-100 p-6 mt-6">
          <h3 className="font-800 text-slate-800 text-base mb-4 flex items-center gap-2">
            <Table2 className="w-5 h-5 text-slate-500" /> Ringkasan Nilai Preferensi (Vᵢ)
          </h3>
          <div className="table-wrap">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-slate-100">
                  <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs">Rank</th>
                  <th className="text-left py-2 px-3 text-slate-500 font-700 text-xs">Alternatif</th>
                  <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">D⁺</th>
                  <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">D⁻</th>
                  <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">Vᵢ = D⁻/(D⁺+D⁻)</th>
                  <th className="text-center py-2 px-3 text-slate-500 font-700 text-xs">Peringkat</th>
                </tr>
              </thead>
              <tbody>
                {ranked.map((a, i) => (
                  <tr key={a.id} className={`${i % 2 === 0 ? 'bg-slate-50/50' : ''} ${i === 0 ? 'bg-yellow-50' : ''}`}>
                    <td className="py-3 px-3">
                      <span className="text-lg">{rankEmojis[i] || `#${a.rank}`}</span>
                    </td>
                    <td className="py-3 px-3">
                      <div className="flex items-center gap-2">
                        <span className="w-6 h-6 flex items-center justify-center bg-violet-100 text-violet-700 text-xs font-800 rounded-md">{a.id}</span>
                        <span className="font-700 text-slate-700">{a.name}</span>
                      </div>
                    </td>
                    <td className="text-center py-3 px-3 font-600 text-red-500 tabular-nums text-xs">{f(a.Dplus || 0)}</td>
                    <td className="text-center py-3 px-3 font-600 text-green-600 tabular-nums text-xs">{f(a.Dminus || 0)}</td>
                    <td className="text-center py-3 px-3 font-800 text-violet-700 tabular-nums">{f(a.Vi)}</td>
                    <td className="text-center py-3 px-3">
                      {i === 0 ? (
                        <span className="badge-winner">#{a.rank} Terbaik!</span>
                      ) : (
                        <span className="font-700 text-slate-500">#{a.rank}</span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  )
}

// ──────── FOOTER ────────
function Footer() {
  return (
    <footer className="bg-slate-900 text-slate-400 py-12 px-4 text-center">
      <div className="max-w-4xl mx-auto">
        <div className="flex items-center justify-center gap-2 mb-4">
          <div className="w-8 h-8 bg-gradient-to-br from-violet-400 to-pink-400 rounded-xl flex items-center justify-center shadow-md">
            <BarChart2 className="w-4 h-4 text-white" />
          </div>
          <span className="font-black text-white text-lg">SPK <span className="text-violet-400">UMKM</span></span>
        </div>
        <p className="text-sm mb-2 font-500">
          Sistem Pendukung Keputusan Pemilihan Metode Pembayaran Digital UMKM
        </p>
        <p className="text-sm font-500">
          Menggunakan Metode <strong className="text-violet-400">TOPSIS</strong> · Berbasis Client-Side React
        </p>
        <div className="mt-6 pt-6 border-t border-slate-800 text-xs text-slate-600">
          © {new Date().getFullYear()} SPK UMKM · Built with ❤️ using React & Tailwind CSS
        </div>
      </div>
    </footer>
  )
}

// ──────── MAIN APP ────────
export default function App() {
  const [alternatives, setAlternatives] = useState(DEFAULT_ALTERNATIVES)
  const [criteria, setCriteria]         = useState(DEFAULT_CRITERIA)

  const result = useMemo(() => {
    try {
      const raw = computeTopsis(alternatives, criteria)
      // Attach D+/D- back to ranked items
      const rankWithD = raw.ranked.map(r => {
        const idx = alternatives.findIndex(a => a.id === r.id)
        return { ...r, Dplus: raw.Dplus[idx], Dminus: raw.Dminus[idx] }
      })
      return { ...raw, ranked: rankWithD }
    } catch {
      return { R: [], Y: [], Aplus: [], Aminus: [], Dplus: [], Dminus: [], Vi: [], ranked: [], W: [] }
    }
  }, [alternatives, criteria])

  const scrollTo = (id) => {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }

  return (
    <div className="min-h-screen">
      <Navbar onScrollTo={scrollTo} />
      <HeroSection onScrollTo={scrollTo} />
      <FeatureSection />
      <DataSection alternatives={DEFAULT_ALTERNATIVES} criteria={DEFAULT_CRITERIA} />
      <CalculatorSection
        alternatives={alternatives}
        setAlternatives={setAlternatives}
        criteria={criteria}
        setCriteria={setCriteria}
      />
      <TopsisStepsSection result={result} criteria={criteria} alternatives={alternatives} />
      <ResultsSection ranked={result.ranked} />
      <Footer />
    </div>
  )
}
