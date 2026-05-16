<?php if(!empty($visitors)): ?>

    <?php $count = 1; ?>

    <?php foreach($visitors as $visitor): ?>

        <tr>

            <td><?= $count++; ?></td>

            <td>
                <?= htmlspecialchars($visitor['full_name']); ?>
            </td>

            <td>
                <?= htmlspecialchars($visitor['phone']); ?>
            </td>

            <td>
                <?= htmlspecialchars($visitor['purpose']); ?>
            </td>

            <td>

                <?php if($visitor['status'] === 'inside'): ?>

                    <span class="status-active">
                        Inside
                    </span>

                <?php else: ?>

                    <span class="status-inactive">
                        Outside
                    </span>

                <?php endif; ?>

            </td>

            <td>

                <?php if($visitor['status'] === 'outside'): ?>

                    <form method="POST"
                          action="index.php?page=checkin_visitor">

                        <input type="hidden"
                               name="visitor_id"
                               value="<?= $visitor['id']; ?>">

                        <button class="btn-success">
                            Check In
                        </button>

                    </form>

                <?php else: ?>

                    <form method="POST"
                          action="index.php?page=checkout_visitor">

                        <input type="hidden"
                               name="visitor_id"
                               value="<?= $visitor['id']; ?>">

                        <button class="btn-danger">
                            Check Out
                        </button>

                    </form>

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>

        <td colspan="6">
            No visitors found
        </td>

    </tr>

<?php endif; ?>