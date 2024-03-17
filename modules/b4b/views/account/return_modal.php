<!-- Reverse Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="returnModalLabel">
          <?= lang("Product refund") ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table class="table">
          <tr>
            <td><?= lang("Invoice") ?></td>
            <td id="ReverseInvoice"></td>
          </tr>
          <tr>
            <td><?= lang("Brand code") ?></td>
            <td id="ReverseCode"></td>
          </tr>
          <tr>
            <td><?= lang("Description") ?></td>
            <td id="ReverseSpecode2"></td>
          </tr>
          <tr>
            <td><?= lang("Original code") ?></td>
            <td id="ReverseSpecode3"></td>
          </tr>
          <tr>
            <td><?= lang("Brand") ?></td>
            <td id="ReverseSpecode"></td>
          </tr>
          <tr>
            <td><?= lang("Product name") ?></td>
            <td id="ReverseName"></td>
          </tr>
          <tr>
            <td><?= lang("Quantity") ?></td>
            <td>
              <input id="ReverseQuantity" min="1" type="number" class="form-control">
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <textarea id="ReverseDesc" placeholder="<?= lang("Description") ?>" class="form-control" rows="4"></textarea>
            </td>
          </tr>

        </table>

      </div>
      <div class="modal-footer">

        <input type="hidden" id="invoice_row_num" />
        <input type="hidden" id="ReversePrice" />
        <input type="hidden" data-name="id" />

        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang("Cancel") ?></button>
        <button type="button" id="ReverseButton" data-role="return-btn" class="btn btn-primary"><?= lang("Confirm") ?></button>

      </div>
    </div>
  </div>
</div>
