<ul class="pagination">
    {{ with(new \App\Pagination\CustomPresenter($paginator))->render() }}
</ul>