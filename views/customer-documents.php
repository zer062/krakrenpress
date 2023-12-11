<div class="alab-container">
	<div class="row">
		<?php if ( wcs_user_has_subscription( get_current_user_id(), '', 'active' )):?>
		<a href="#ex1" class="alab-btn">Criar documento</a>
		<?php endif; ?>
	</div>
	<div class="row">
		<?php if (count($documents) > 0) : ?>
		<table>
			<thead>
				<tr>
					<th>Documento</th>
					<th>Paciente</th>
					<th>Link Clicksign</th>
					<th>Status</th>
					<th>Data de Geração</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($documents as $document) :
				$patient_data = get_post_meta($document->ID, 'patient_data', true);
				$patient_data = unserialize($patient_data);
				$patient_sign_url = get_post_meta($document->ID, 'document_clicksign_patient_signer_url', true);
				$doctor_sign_url = get_post_meta($document->ID, 'document_clicksign_doctor_signer_url', true);
				$document_status = get_post_meta($document->ID, 'document_clicksign_status', true);
			?>
				<tr>
					<td><?php echo $document->post_title; ?></td>
					<td><?php echo $patient_data['nome']; ?></td>
					<td>
						<?php if ($doctor_sign_url) :?>
						<a href="<?php echo $doctor_sign_url ?? '#';?>" target="_blank">Via do Médico</a><br>
						<?php endif; ?>
						<?php if ($doctor_sign_url) :?>
						<a href="<?php echo $patient_sign_url ?? '#';?>" target="_blank">Via do Paciente</a>
						<?php endif; ?>
					</td>
					<td>
						<?php
							switch ($document_status) {
								case 'closed':
									echo 'Documento finalizado';
									break;
								case 'canceled':
									echo 'Documento cancelado';
									break;
								default:
									echo 'Em processo';
									break;
							}
						?>
					</td>
					<td><?php echo get_the_date('d/m/Y H:i', $document->ID); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php else: ?>
		<div class="alab-warning">
			<?php _e('Nenhum documento gerado ainda!', 'alab'); ?>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php if ( wcs_user_has_subscription( get_current_user_id(), '', 'active' )):?>
<div id="ex1" class="modal">
	<div class="row">
		<div class="col c-50 form">
			<div class="row">
				<h3>Preencha os dados do paciente</h3>
			</div>
			<form action="" id="alab-doc-form">
				<div class="alab-form-fields">
					<div class="row">
						<div class="col">
							<label class="alab-label" for="alab-doc-template">Template: </label>
							<select name="template" id="alab-doc-template" class="select2">
								<option value="">Selecione um template</option>
								<?php foreach ($templates as $template) : ?>
									<option value="<?php echo $template->ID; ?>"><?php echo $template->post_title; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div id="alab-doc-fields"></div>
						</div>
					</div>
					<div class="row">
						<span style="color: #fff;"><small>Campos com * são obrigatórios</small></span>
					</div>
				</div>
				<div class="row form-footer">
					<a href="#" class="alab-btn-cancel" rel="modal:close">Cancelar</a>
					<button id="alab-generate-preview" type="submit" class="alab-btn">Gerar documento</button>
				</div>
			</form>
		</div>
		<div class="col c-50 preview">
			<div class="row">
				<h3>Pré-visualização</h3>
			</div>
			<div class="paper">
				<div class="row">
					<div class="col">
						<div id="alab-doc-preview"></div>
					</div>
				</div>
				<div class="row form-footer">
					<button id="alab-generate-doc" class="alab-btn" disabled>Enviar documento para assinatura</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
