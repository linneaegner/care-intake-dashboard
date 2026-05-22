# Care Intake Dashboard

Ett kompakt fullstack-projekt för fiktiv ärendehantering på en psykologmottagning. Byggt som portfolio-demo för att visa snabb inlärning av **Laravel**, **Vue 3** och **Tailwind CSS** — med fokus på API-first-arkitektur, tillgänglighet och tydlig domänlogik.

> Demo med fiktiva alias. Inga riktiga personuppgifter används.

**Repo:** [github.com/linneaegner/care-intake-dashboard](https://github.com/linneaegner/care-intake-dashboard)

---

## Stack

| Lager | Teknik |
|-------|--------|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Vue 3, Vue Router, Vite |
| Styling | Tailwind CSS 4 |
| Databas | SQLite (lokal utveckling) |
| Tester | PHPUnit Feature tests |

---

## Funktioner

- Lista inkommande ärenden med alias, kontaktkanal, typ, prioritet och status
- Filtrera på status och prioritet, sök på alias eller ärendetyp
- Registrera nytt ärende med server-side validering och tydliga felmeddelanden
- Detaljvy med sammanfattning, intern anteckning och tidslinje
- Uppdatera status via API utan sidomladdning
- WCAG-grunder: labels, focus states, semantisk HTML, `aria-live` vid uppdateringar

---

## Arkitektur

```
Vue SPA (resources/js/)
    ↓ fetch
Laravel API (/api/cases)
    ↓
Controller → Form Request → Service → Model
    ↓
API Resource → JSON
```

**Backend-lager**

- `Enums/` — domänregler (status, prioritet, ärendetyp m.m.)
- `Http/Requests/` — validering
- `Services/IntakeCaseService` — affärslogik och tidslinje
- `Http/Resources/` — konsekvent JSON till frontend

**Frontend-komponenter**

| Komponent | Ansvar |
|-----------|--------|
| `CaseList` | Tabell med ärenden |
| `CaseFilters` | Filter och sök |
| `CaseForm` | Skapa ärende |
| `CaseDetail` | Detalj, status, anteckning, tidslinje |
| `StatusBadge` | Färgkodade etiketter |

---

## Kom igång

### Krav

- PHP 8.3+
- Composer
- Node.js 18+
- npm

### Installation

```bash
git clone https://github.com/linneaegner/care-intake-dashboard.git
cd care-intake-dashboard

composer install
cp .env.example .env
php artisan key:generate

# SQLite
touch database/database.sqlite

# Säkerställ DB_CONNECTION=sqlite i .env
php artisan migrate --seed

npm install
npm run build
```

### Starta lokalt

**Alternativ A — produktionsbyggd frontend (en terminal):**

```bash
php artisan serve
```

Öppna http://127.0.0.1:8000

**Alternativ B — utvecklingsläge med hot reload (två terminaler):**

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Öppna http://127.0.0.1:8000 (inte Vite-porten direkt).

### Tester

```bash
php artisan test
```

---

## API

| Metod | Endpoint | Beskrivning |
|-------|----------|-------------|
| GET | `/api/cases` | Lista ärenden (`?status=`, `?priority=`, `?search=`) |
| POST | `/api/cases` | Skapa ärende |
| GET | `/api/cases/{id}` | Hämta ärende med tidslinje |
| PATCH | `/api/cases/{id}/status` | Uppdatera status |
| PATCH | `/api/cases/{id}/note` | Uppdatera intern anteckning |

---

## Projektstruktur (urval)

```
app/
├── Enums/              # Domänenums
├── Http/
│   ├── Controllers/Api/
│   ├── Requests/
│   └── Resources/
├── Models/
└── Services/

resources/js/
├── api/cases.js        # API-klient
├── components/         # Vue-komponenter
├── composables/        # Delad state
├── views/              # Sidor
├── App.vue
└── router.js

tests/Feature/Api/      # API-tester
```

---

## Portfolio case study

### Kort version (1–2 meningar)

> Ett kompakt Laravel + Vue + Tailwind-projekt där jag byggde ett API-first-flöde för vårdrelaterad ärendehantering, med fokus på tillgänglighet, tydlig statuslogik och responsiv UX.

### Längre version (för portfolio / ansökan)

**Care Intake Dashboard**

*Laravel + Vue 3 + Tailwind — API-first ärendehantering*

**Bakgrund**  
Jag ville visa att jag snabbt kan sätta mig in i en ny stack och leverera ett genomtänkt, litet men komplett projekt — relevant för digitala lösningar inom vård och psykologi.

**Problem**  
En mottagning behöver hantera inkommande vårdförfrågningar strukturerat: registrera, prioritera, följa status och dokumentera internt — utan att exponera riktiga personuppgifter i en demo.

**Lösning**  
Ett API-first dashboard där administratör kan registrera ärenden, filtrera listan, uppdatera status och se en tidslinje över händelser. All data är fiktiv (alias som "Klient A", "Referens 1042").

**Tekniska val**

- **Laravel Enums + Form Requests** — tydliga domänregler och validering
- **Service-lager** — statusbyte loggas automatiskt i tidslinjen
- **Vue 3 Composition API** — separerad API-klient, små komponenter med ett ansvar vardera
- **SQLite** — zero-config lokal setup
- **Feature-tester** — 9 tester för API och validering
- **Tillgänglighet** — labels, keyboard navigation, focus states, `aria-live`

**Resultat**  
Fungerande fullstack-demo som visar att jag kan lära mig Laravel, Vue och Tailwind snabbt — med fokus på kodkvalitet, UX och underhållbar struktur snarare än feature-mängd.

**Vad jag hade gjort vidare**

- Autentisering för admin
- Paginering vid större datamängd
- E2E-tester (Playwright)
- Deploy till t.ex. Laravel Forge / Vercel (frontend) eller single-server

---

## Licens

MIT
