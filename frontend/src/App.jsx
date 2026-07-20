import { useEffect, useState } from 'react'
import VulnerabilityTable from './components/VulnerabilityTable'
import VulnerabilityForm from './components/VulnerabilityForm'
import VulnerabilityDetailModal from './components/VulnerabilityDetailModal'

const API_BASE_URL = 'http://127.0.0.1:8000/api'

function App() {
  const [vulnerabilities, setVulnerabilities] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [showForm, setShowForm] = useState(false)
  const [selectedVulnerability, setSelectedVulnerability] = useState(null)

  useEffect(() => {
    fetchVulnerabilities()
  }, [])

  async function fetchVulnerabilities() {
    try {
      setLoading(true)
      const response = await fetch(`${API_BASE_URL}/vulnerabilities`)

      if (!response.ok) {
        throw new Error(`Request gagal dengan status ${response.status}`)
      }

      const data = await response.json()
      setVulnerabilities(data.data)
      setError(null)
    } catch (err) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  function handleFormSuccess() {
    fetchVulnerabilities()
    setShowForm(false)
  }

  return (
    <div className="min-h-screen bg-slate-900 px-6 py-10 text-slate-100">
      <div className="mx-auto max-w-6xl space-y-6">
        <header className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-white">
              Vulnerability Management Dashboard
            </h1>
            <p className="mt-1 text-sm text-slate-400">
              Daftar temuan vulnerability dari hasil pentest
            </p>
          </div>
          <button
            onClick={() => setShowForm((prev) => !prev)}
            className="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500"
          >
            {showForm ? 'Tutup Form' : '+ Tambah Temuan'}
          </button>
        </header>

        {showForm && <VulnerabilityForm onSuccess={handleFormSuccess} />}

        {loading && <p className="text-slate-400">Memuat data...</p>}

        {error && (
          <div className="rounded-lg border border-red-500/50 bg-red-500/10 px-4 py-3 text-red-400">
            Gagal mengambil data: {error}
          </div>
        )}

        {!loading && !error && (
          <VulnerabilityTable
            vulnerabilities={vulnerabilities}
            onRowClick={setSelectedVulnerability}
          />
        )}
      </div>

      <VulnerabilityDetailModal
        vulnerability={selectedVulnerability}
        onClose={() => setSelectedVulnerability(null)}
      />
    </div>
  )
}

export default App