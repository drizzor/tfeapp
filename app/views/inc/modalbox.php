<div class="modal fade" id="<?= isset($modalId) ? $modalId : '' ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= isset($title) ? $title : '' ?></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span class="btn-close" aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form class="form" action="<?= isset($actionLink) ? $actionLink : '' ?>" role="form" method="post">
                <div class="modal-body">
                    <p class="alert alert-<?= isset($alert) ? $alert : 'danger' ?>"><?= isset($message) ? ' ' . $message : '' ?></p>
                </div>

                <?php if(isset($input_sold) && $input_sold): ?>
                <div class="form-group col-lg-11">
                    <label for="price">Prix de la vente</label>
                    <input type="text" class="form-control form-control-lg" id="price" name="price" placeholder="Veuillez indiquer le prix de vente">
                </div>
                <div class="form-group col-lg-11">
                    <label for="date">Date mise en vente</label>
                    <input type="date" class="form-control form-control-lg" id="date" name="date" placeholder="Date de mise en vente">
                </div>

                <?php elseif(isset($input_selectUser)): ?>
                <div class="form-group col-lg-11">
                    <label for="user">Migrer les données à<sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                    <select class="form-control form-control-lg" id="user" name="user">
                        <?php foreach($data['users'] as $user): ?>
                            <?php if($user['id'] == $data['user_id']): ?>
                            <option selected="selected" value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                            <?php else: ?>
                            <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php elseif(isset($input_planning)): ?>
                <div class="form-group col-lg-11">
                    <label for="salary">Taux horaire</label>
                    <input type="text" class="form-control form-control-lg" id="salary" name="salary" value="<?= $salary ?>">
                </div>
                <div class="form-group col-lg-11">
                    <label for="hours">Total heures prestées</label>
                    <input type="text" class="form-control form-control-lg" id="hours" name="hours" value="<?= $hour ?>">
                </div>
                
                <?php elseif(isset($input_gallery)): ?>
                <div class="form-group col-lg-11">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Vous pouvez mettre un titre à l'image..." value="<?= $g_title ?>">
                </div>
                <div class="form-group col-lg-11">
                    <label for="description">Description</label>
                    <textarea id="textarea_count" rows="8" cols="30" maxlength="255" type="text" class="form-control form-control-lg" id="description" name="description" placeholder="Vous aussi mettre un petit commentaire pour accompagner l'image"><?= $description ?></textarea>
                    <div id="textarea_feedback" style="color:#21cab5"></div>               
                </div>
                <div class="form-group form-check">                    
                    <label class="checkboxContainer">En cours de rénovation?
						<input type="checkbox" name="inProgress" class="form-check-input" id="inProgress" <?= $isCheck ?>>
						<span class="checkmark"></span>
					</label>
				</div>

                <?php endif; ?>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-<?= isset($btn) ? $btn : 'danger' ?>"><i class="far fa-paper-plane"></i> <?= isset($actionButton) ? $actionButton : '???' ?></button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>