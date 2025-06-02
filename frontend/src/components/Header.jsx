import PerfilSwitcher from './PerfilSwitcher'

function Header() {
  return (
    <header className="flex justify-between items-center px-6 py-4 bg-white shadow w-full">
      <h1 className="text-xl font-semibold text-gray-700">Panel de gestión</h1>
      <PerfilSwitcher />
    </header>
  )
}

export default Header
