<template>
  <div class="min-h-screen flex flex-col">
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:shadow-md"
    >
      Hoppa till innehåll
    </a>

    <header class="border-b border-slate-200 bg-white">
      <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
          <p class="text-sm font-medium text-care-700">Care Intake Dashboard</p>
          <h1 class="text-lg font-semibold text-slate-900">
            Psykologmottagning – ärendehantering
          </h1>
        </div>

        <nav aria-label="Huvudnavigation">
          <ul class="flex flex-wrap gap-2">
            <li>
              <RouterLink
                to="/"
                class="inline-flex rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-care-100 hover:text-care-900"
                active-class="bg-care-100 text-care-900"
              >
                Ärenden
              </RouterLink>
            </li>
            <li>
              <RouterLink
                to="/cases/new"
                class="inline-flex rounded-md bg-care-700 px-3 py-2 text-sm font-medium text-white hover:bg-care-800"
                active-class="ring-2 ring-care-700 ring-offset-2"
              >
                Nytt ärende
              </RouterLink>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <main id="main-content" class="flex-1">
      <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <RouterView />
      </div>
    </main>

    <footer class="border-t border-slate-200 bg-white">
      <div class="mx-auto max-w-6xl px-4 py-4 text-sm text-slate-600 sm:px-6">
        Demo med fiktiva alias — inga riktiga personuppgifter används.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>
