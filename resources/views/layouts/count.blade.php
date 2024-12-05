<link rel="stylesheet" href="{{ env('LOCAL') ? '' : '/public' }}/css/styles.css">
<style>
    .count {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;

        background-color: var(--secondary);
        height: 7.5rem;

        border-radius: 1.5rem;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
        width: 20em;
        min-width: max-content;
        padding: 0.5em;

        h2 {
            margin: 0;
            font-size: 1.2em;
        }

        img {
            height: 200%;
        }
    }

    .count.resume {}

    .count.interview {}

    .total {
        white-space: nowrap;
        cursor: default;
    }
</style>
<div class="count resume">
    <img class="image" src="{{ env('LOCAL') ? '' : '/public' }}/icons/profiling.svg" alt="Profiling" height="280">
    <h2 class="total">Total {{ $resumes->count() === 1 ? 'Resume' : 'Resumes' }}: {{ $resumes->count() }}</h2>
</div>

<div class="count hire">
    <img class="image" src="{{ env('LOCAL') ? '' : '/public' }}/icons/hired.svg" alt="Profiling" height="280">
    <h2 class="total">Total hires: {{ $counts['hired'] }}</h2>
</div>

<div class="count interview">
    <img class="image" src="{{ env('LOCAL') ? '' : '/public' }}/icons/interview.svg" alt="Profiling" height="280">
    <h2 class="total">Total interviews: {{ $counts['interview'] }}</h2>
</div>

<div class="count apply">
    <img class="image" src="{{ env('LOCAL') ? '' : '/public' }}/icons/applied.svg" alt="Profiling" height="280">
    <h2 class="total">Total applications: {{ $counts['total'] }}</h2>
</div>

<div class="count other">
    <img class="image" src="{{ env('LOCAL') ? '' : '/public' }}/icons/other.svg" alt="Profiling" height="280">
    <h2 class="total">Total others: {{ $counts['others'] }}</h2>
</div>
