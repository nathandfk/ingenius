
  
  <!-- page-tableau-personnalise -->
        <div class="progress-wrapper">
            <div class="progress-titles">
                <h2 class="progress-title">Etapa 1: selecione sua imagem</h2>
                <h2 class="progress-title">Passo 2: Configure sua placa</h2>
                <h2 class="progress-title">Passo 3: Adicionar ao carrinho</h2>
            </div>
            <div class="progress-bar"><div class="progress-bar-enabled"></div></div>
        </div>
        <br>
        <div class="tab-form-wrapper">
            <div class="tab-form-col">
                <div class="form-col-1">
                    <div class="personalized-pictures">
                        <div class="personalized-picture">
                            <img src="<?php echo get_theme_file_uri('/custom-table/assets/pictures/4.jpeg'); ?>"
                            srcset="<?php echo get_theme_file_uri('/custom-table/assets/pictures/4.jpeg'); ?>">
                            <div class="tabloide-card-img tabloide-size_<?php echo strtolower($size); ?>" style="background-image: url('<?php echo $bgImage; ?>')"></div>
                            <div class="tabloide-personalized-loading d-none"></div>
                        </div>
                        <div class="personalized-picture">
                            <img src="<?php echo get_theme_file_uri('/custom-table/assets/pictures/5.jpeg'); ?>"
                            srcset="<?php echo get_theme_file_uri('/custom-table/assets/pictures/5.jpeg'); ?>">
                            <div class="tabloide-card-img tabloide-size_<?php echo strtolower($size); ?>" style="background-image: url('<?php echo $bgImage; ?>')"></div>
                            <div class="tabloide-personalized-loading d-none"></div>
                        </div>
                        <div class="personalized-picture">
                            <img src="<?php echo get_theme_file_uri('/custom-table/assets/pictures/6.jpeg'); ?>"
                            srcset="<?php echo get_theme_file_uri('/custom-table/assets/pictures/6.jpeg'); ?>">
                            <div class="tabloide-card-img tabloide-size_<?php echo strtolower($size); ?>" style="background-image: url('<?php echo $bgImage; ?>')"></div>
                            <div class="tabloide-personalized-loading d-none"></div>
                        </div>
                    </div>
                    
                    <div class="personalized-arrows">
                        <div class="personalized-arrow personalized-arrow-left" style="display: none;"><i class="fas fa-chevron-left"></i></div>
                        <div class="personalized-arrow personalized-arrow-right"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
                <form class="form-col-2" id="form-col-2" method="POST" enctype='multipart/form-data'>
                    <div class="personalized-form">
                    <h1 id="personalized-title"><?php echo $products->name; ?></h1>
                    <input type="hidden" name="tabloide-personalized-id" value="<?php echo $id; ?>">
                    <input type="hidden" name="tabloide-personalized-attribute-id" class="tabloide-personalized-attribute-id" id="tabloide-personalized-attribute-id" value="">

                    <p class="custom-table-short-product"><?php echo $products->short_description; ?></p>
                    <p class="tabloide-text-confirm"></p>
                    <div class="tabloide-paginate-wrapper">
                        <div class="tabloide-paginate-step">
                            <!-- Images -->
                            <div class="tabloide-picture-card tabloide-step" data-step="picture">
                                <p class="custom-table-resolution">Dimensões mínimas: 2000 x 2000 px</p>
                                <div class="tabloide-upload-inner">
                                    <label class="tabloide-upload-file cp" for="tabloide-upload">
                                        <div class="tabloide-upload-icon"><i class="fas fa-upload"></i></div>
                                        <div class="tabloide-upload-text">Clique aqui para enviar sua imagem <span class="red-color">*</span></div>
                                    </label>
                                </div>
                                <input type="file" name="tabloide-upload" id="tabloide-upload" accept="image/heic, image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                                
                                <div class="tabloide-insert-url">
                                    <div><a href="" class="tabloide-link-click">Inserir uma imagem através de um link</a></div>
                                    <div class="tabloide-link-upload-inner tabloide-link-upload-disabled">
                                        <input type="text" placeholder="http://tabloide.fr/" name="tabloide-link-upload" class="tabloide-link-upload">
                                        <button class="tabloide-uploaded-validated">para validar</button>
                                    </div>
                                </div>

                                <div class="tabloide-disposition">
                                    <input type="checkbox" name="tabloide-disposition-check" id="tabloide-disposition-check">
                                    <label for="tabloide-disposition-check">Modo retrato (quadro vertical)</label>
                                </div>
                                
                                <button class="click click-dark tabloide-validate" id="go-to-step" data-step-next="2">Configurar minha placa</button>
                            </div>

                            <div class="tabloide-attribute tabloide-step">
                                <!-- Tailles -->
                                <div class="tabloide-step" data-step="size">
                                    <div class="tabloide-size">
                                        <h2 class="tabloide-title size18">Tamanhos</h2>
                                        
                                        <div class="tabloide-size-display tabloide-bull-display">
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="xs" ? 'attribute-size-selected' : ''; ?>" data-size-slug="xs">XS</div>
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="s" ? 'attribute-size-selected' : ''; ?>" data-size-slug="s">S</div>
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="m" ? 'attribute-size-selected' : ''; ?>" data-size-slug="m">M</div>
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="l" ? 'attribute-size-selected' : ''; ?>" data-size-slug="l">L</div>
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="xl" ? 'attribute-size-selected' : ''; ?>" data-size-slug="xl">XL</div>
                                            <div class="tabloide-bull tabloide-bull-sm tabloide-bull-size <?php echo $size=="xxl" ? 'attribute-size-selected' : ''; ?>" data-size-slug="xxl">XXL</div>
                                        </div>
                                    </div>
                                    <ul class="tabloide-size-guid-wrapper">
                                        <li class="tabloide-size-guid">
                                            <a>Guia de tamanho</a>
                                            <ul>
                                                <li>XS : 13cmx18cm x1</li>
                                                <li>S : 21cmx30cm x1</li>
                                                <li>M : 30cmx45cm x1</li>
                                                <li>L: 40cmx60cm x1</li>
                                                <li>XL: 60cmx80cm x1</li>
                                                <li>XXL: 70cmx100cm x1</li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <input type="hidden" class="tabloide-size-input" name="tabloide-size-input" value="<?php echo $size; ?>">
                                </div>

                                <!-- Support -->
                                <div class="tabloide-support tabloide-step" data-step="support">
                                    <h2 class="tabloide-support-title size18">Apoio</h2>
                                    <div class="tabloide-support-display tabloide-bull-display">
                                        <div class="tabloide-bull tabloide-bull-lg tabloide-bull-support <?php echo $support=="avec-chassis" ? 'attribute-support-selected' : ''; ?>" data-support-slug="avec-chassis" title="Com moldura"></div>
                                        <div class="tabloide-bull tabloide-bull-lg tabloide-bull-support <?php echo $support=="toiles-seulement" ? 'attribute-support-selected' : ''; ?>" data-support-slug="toiles-seulement" title="Apenas lona(s)"></div>
                                    </div>
                                    <input type="hidden" class="tabloide-support-input" name="tabloide-support-input" value="<?php echo $support; ?>">
                                </div>
                                <button class="click click-dark return-step-prev" data-step-prev="0"><i class="fas fa-arrow-left"></i> Voltar ao passo anterior</button>
                                <button class="click click-dark tabloide-validate tabloide-step-attribute" id="go-to-step" data-step-next="3">Escolha a quantidade</button>
                            </div>

                            <div class="tabloide-personalized-last-step tabloide-step">
                                <div class="tabloid-price">
                                    <span></span>
                                </div>
                                <div class="quantity-and-cart ">
                                    <input type="number" name="tabloide-personalized-quantity" class="form-control" min="1" max="50" value="1" class="">
                                    <button class="click click-dark" id="single-add-to-cart">Adicionar ao cesto</button>
                                </div>
                                <button class="click click-dark return-step-prev" data-step-prev="100"><i class="fas fa-arrow-left"></i> Voltar ao passo anterior</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>