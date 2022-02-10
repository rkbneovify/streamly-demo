
<div class="card">
    <div class="card-header"
    @isset($cardHeadingId)
        id="{{ $cardHeadingId }}"
    @endisset
    >
      <h2 class="mb-0">
        <button 
        class="btn btn-link collapsed" 
        type="button" 
        data-toggle="collapse"
        @isset($cardCollapseId)
            data-target="{{ '#' . $cardCollapseId }}"
        @endisset
        aria-expanded="true"
        @isset($cardCollapseId)
            aria-controls="{{ $cardCollapseId }}">
        @endisset
        @isset($cardTitle)
            {{ $cardTitle }}
        @endisset
        </button>
      </h2>
    </div>
    <?php $yo=$index==0?'show':''; ?>
    <div id="{{ $cardCollapseId }}" class="{{'collapse  ' .$yo  }}"
    @isset($cardHeadingId)
        aria-labelledby="{{ $cardHeadingId }}"
    @endisset
    @isset($parentId)
        data-parent="{{ '#' . $parentId }}">
    @endisset
      <div class="card-body">
        @isset($cardBody)
          {{ $cardBody }}
        @endisset
      </div>
    </div>
</div>