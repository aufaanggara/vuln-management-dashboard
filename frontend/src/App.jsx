import { useEffect, useState } from 'react'
import { AuthProvider, useAuth } from './context/AuthContext'
import LoginPage from './pages/LoginPage'
import VulnerabilityTable from './components/VulnerabilityTable'
import VulnerabilityForm from './components/VulnerabilityForm'
import VulnerabilityDetailModal from './components/VulnerabilityDetailModal'
import VulnerabilityCharts from './components/VulnerabilityCharts'

const API_BASE_URL = 'http://127.0.0.1:8000/api'

function Dashboard() {
  const { user, token, logout } = useAuth()
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
      const response = await fetch(`${API_BASE_URL}/vulnerabilities`, {
        headers: { Authorization: `Bearer ${token}` },
      })

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

  async function handleExportPdf() {
    try {
      const response = await fetch(`${API_BASE_URL}/vulnerabilities/export/pdf`, {
        headers: { Authorization: `Bearer ${token}` },
      })

      if (!response.ok) {
        throw new Error('Gagal mengunduh laporan PDF')
      }

      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = `vulnerability-report-${new Date().toISOString().slice(0, 10)}.pdf`
      document.body.appendChild(link)
      link.click()
      link.remove()
      window.URL.revokeObjectURL(url)
    } catch (err) {
      setError(err.message)
    }
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
              Masuk sebagai {user?.name} ({user?.role})
            </p>
          </div>
          <div className="flex items-center gap-3">
            <button
              onClick={handleExportPdf}
              className="rounded-md border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800"
            >
              Export PDF
            </button>
            {user?.role === 'pentester' && (
              <button
                onClick={() => setShowForm((prev) => !prev)}
                className="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500"
              >
                {showForm ? 'Tutup Form' : '+ Tambah Temuan'}
              </button>
            )}
            <div className="flex items-center gap-2 rounded-md border border-slate-700 bg-slate-800/50 px-3 py-2">
              <span className="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-xs font-semibold text-white">
                {user?.name?.charAt(0).toUpperCase()}
              </span>
              <span className="text-sm text-slate-300">{user?.name}</span>
            </div>
            <button
              onClick={logout}
              className="rounded-md border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800"
            >
              Logout
            </button>
          </div>
        </header>

        {!loading && !error && <VulnerabilityCharts vulnerabilities={vulnerabilities} />}

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
        onUpdated={fetchVulnerabilities}
      />
    </div>
  )
}

function AppContent() {
  const { user, loading } = useAuth()

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-slate-900 text-slate-400">
        Memuat...
      </div>
    )
  }

  return user ? <Dashboard /> : <LoginPage />
}

function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  )
}

export default App