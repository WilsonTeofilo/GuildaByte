<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | GuildaByte</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- CSS Original Mantedo para Componentes Específicos -->
  <link rel="stylesheet" href="{{ asset('style/design-system.css?v=1.0') }}">
  <link rel="stylesheet" href="{{ asset('style/shell.css?v=1.2') }}">
</head>
<body class="bg-gray-900 text-white font-sans antialiased">
  <div class="flex h-screen bg-gray-900">
      <!-- Sidebar -->
      <aside class="w-64 bg-gray-800 border-r border-gray-700">
          <div class="p-4 border-b border-gray-700">
              <span class="text-xl font-bold text-purple-400">GuildaByte Admin</span>
          </div>
          <nav class="p-4 space-y-2">
              <a href="#" class="block p-2 rounded hover:bg-gray-700 text-gray-300">Dashboard</a>
              <a href="#" class="block p-2 rounded hover:bg-gray-700 text-gray-300">Projetos</a>
              <a href="#" class="block p-2 rounded hover:bg-gray-700 text-gray-300">Clientes</a>
          </nav>
          <div class="p-4 border-t border-gray-700 absolute bottom-0 w-64">
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left p-2 rounded text-red-400 hover:bg-gray-700">
                      Sair
                  </button>
              </form>
          </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8 overflow-y-auto">
          <h1 class="text-3xl font-bold mb-6 text-green-300">Mundo Admin</h1>
          <div class="grid grid-cols-3 gap-6">
              <div class="p-6 bg-gray-800 rounded-lg shadow border border-gray-700">
                  <h3 class="text-gray-400 text-sm font-semibold uppercase">Total Projetos</h3>
                  <p class="text-4xl font-bold text-white mt-2">12</p>
              </div>
              <div class="p-6 bg-gray-800 rounded-lg shadow border border-gray-700">
                  <h3 class="text-gray-400 text-sm font-semibold uppercase">Receita Mensal</h3>
                  <p class="text-4xl font-bold text-white mt-2">R$ 15.000</p>
              </div>
              <div class="p-6 bg-gray-800 rounded-lg shadow border border-gray-700">
                  <h3 class="text-gray-400 text-sm font-semibold uppercase">Clientes Ativos</h3>
                  <p class="text-4xl font-bold text-white mt-2">8</p>
              </div>
          </div>
      </main>
  </div>
</body>
</html>

