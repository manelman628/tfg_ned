<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation">
    <ul class="pagination">
      <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="Primer">
              <span aria-hidden="true">Primer</span>
          </a>
      </li>
      <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Anterior selecció" title="Anterior selecció" data-toggle="tooltip" data-placement="bottom">
              <span aria-hidden="true">&laquo;</span>
          </a>
      </li>
      <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Anterior" title="Anterior" data-toggle="tooltip" data-placement="bottom">
              <span aria-hidden="true">&lsaquo;</span>
          </a>
      </li>

    <?php foreach ($pager->links() as $link) : ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
            <a class="page-link" href="<?= $link['uri'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

      <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Següent" title="Següent" data-toggle="tooltip" data-placement="bottom">
              <span aria-hidden="true">&rsaquo;</span>
          </a>
      </li>
      <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Següent selecció" title="Següent selecció" data-toggle="tooltip" data-placement="bottom">
              <span aria-hidden="true">&raquo;</span>
          </a>
      </li>
      <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
          <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Últim">
              <span aria-hidden="true">Últim</span>
          </a>
      </li>
    </ul>
</nav>
