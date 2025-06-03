import Bandeja from './Bandeja'
import Sidebar from '../components/Sidebar'
import Header from '../components/layout/Header'

function Dashboard() {
  return (
    <div className="flex">
      <Sidebar />
      <div className="flex-1 flex flex-col bg-gray-50 min-h-screen">
        <Header />
        <main className="flex-1 p-6">
          <Bandeja />
        </main>
      </div>
    </div>
  )
}

export default Dashboard
