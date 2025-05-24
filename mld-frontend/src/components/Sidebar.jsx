import { Home, Calendar, ClipboardList, Repeat } from 'lucide-react'

function Sidebar() {
  return (
    <aside className="w-20 bg-white shadow h-screen flex flex-col items-center py-6 space-y-8">
      <img src="/logo192.png" alt="Logo" className="w-12 mb-4" />
      <Home className="w-6 h-6 text-gray-600 hover:text-teal-600 cursor-pointer" />
      <ClipboardList className="w-6 h-6 text-gray-600 hover:text-teal-600 cursor-pointer" />
      <Calendar className="w-6 h-6 text-gray-600 hover:text-teal-600 cursor-pointer" />
      <Repeat className="w-6 h-6 text-gray-600 hover:text-teal-600 cursor-pointer" />
    </aside>
  )
}

export default Sidebar
