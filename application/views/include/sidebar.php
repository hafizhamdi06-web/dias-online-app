<aside class="main-sidebar sidebar-dark-purple elevation-0 bg-primary">
    <a href="javascript:void(0)" class="brand-link border-0 mb-2" tabindex="-1">
      <img src="<? echo app_url('assets/dist/img/logo-utama.png'); ?>" alt="Logo" class="brand-image mt-2">
      <span class="brand-text text-xl ml-2"><?= $app_name; ?></span>
    </a>
    <span id="vendor-text" class="vendor-text text-sm text-light" href="javascript:void(0)" 
        style="margin-top:-30px;margin-left:80px;position:absolute;">
        <?= $vendor_text;?>
    </span>      
    <div class="sidebar">
      <nav class="mt-1">
        <ul class="nav nav-pills nav-sidebar nav-collapse-hide-child nav-compact flex-column pb-4" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item mt-2">
            <a href="page/laporan" class="nav-link text-sm" role="Laporan" onClick="_hideSidebarFocused();">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Laporan
                <!--
                <span class="right badge badge-danger">Baru</span>                                                
                -->
              </p>
            </a>
          </li> 
          <?php
            $query  = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
             WHERE A.mtype=2 AND A.mactive=1 AND A.mparent=0 ORDER BY A.murutan ASC, A.mparent ASC";
            $sql = $this->db->query($query);        
            $jumlah = $sql->num_rows();
            if($jumlah>0){
          ?>
            <div class="dropdown-divider" style="opacity:.3"></div>       
            <li class="nav-header pt-0">Transaksi</li>
          <?php              
            }

            foreach ($sql->result() as $row) {
          ?>
              <li class='nav-item has-treeview'>           
                <a href="#" class="nav-link text-sm">
                  <i class="nav-icon <?= $row->MICON; ?>"></i>
                  <p>
                    <?= $row->MCAPTION1; ?>
                    <i class="fas fa-angle-right right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">                
          <?php
              $queryc = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
                        WHERE A.mparent=".$row->MID." AND A.mactive=1 ORDER BY A.murutan ASC";
              $sqlc = $this->db->query($queryc);
              $r = 0;
              foreach ($sqlc->result() as $row_c) {                                     
          ?>
              <li class="nav-item">
                  <a href="<?= $row_c->MLINK; ?>" class="nav-link text-sm" role="<?= $row_c->MCAPTION1; ?>" onClick="_hideSidebarFocused();">
                  <i class="fas fa-caret-right nav-icon"></i>                  
                  <p><?= $row_c->MCAPTION1; ?></p>
                </a>
              </li>              
          <?php
                $r++;
              }
              echo "</ul></li>";                           
            }
          ?>

          <?php
            $query  = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
             WHERE A.mtype=3 AND A.mactive=1 AND A.mparent=0 ORDER BY A.murutan ASC, A.mparent ASC";
            $sql = $this->db->query($query);        
            $jumlah = $sql->num_rows();
            if($jumlah>0){
          ?>          
            <div class="dropdown-divider" style="opacity: .3"></div>             
            <li class="nav-header pt-0">Master Data</li>                          
          <?php              
            }

            foreach ($sql->result() as $row) {  
              if(empty($row->MLINK)){          
          ?>          
              <li class='nav-item has-treeview'>           
                <a href="#" class="nav-link text-sm">
                  <i class="nav-icon <?= $row->MICON; ?>"></i>
                  <p>
                    <?= $row->MCAPTION1; ?>
                    <i class="fas fa-angle-right right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">                
          <?php
              } else {
          ?>
              <li class='nav-item'>           
                <a href="<?= $row->MLINK; ?>" class="nav-link text-sm" role="<?= $row->MCAPTION1; ?>" onClick="_hideSidebarFocused();">
                  <i class="nav-icon <?= $row->MICON; ?>"></i>
                  <p>
                    <?= $row->MCAPTION1; ?>
                  </p>
                </a>
              </li>
          <?php                
              }
              $queryc = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
                        WHERE A.mparent=".$row->MID." AND A.mactive=1 ORDER BY A.murutan ASC";
              $sqlc = $this->db->query($queryc);
              $r = 0;
              foreach ($sqlc->result() as $row_c) {                                     
          ?>
              <li class="nav-item">
                  <a href="<?= $row_c->MLINK; ?>" class="nav-link text-sm" role="<?= $row_c->MCAPTION1; ?>" onClick="_hideSidebarFocused();">
                  <i class="fas fa-caret-right nav-icon"></i>                  
                  <p><?= $row_c->MCAPTION1; ?></p>
                </a>
              </li>              
          <?php
                $r++;
              }
              if(empty($row->MLINK)){          
                echo "</ul></li>";                           
              }
            }
          ?>

          <?php
            $query  = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
             WHERE A.mtype=4 AND A.mactive=1 AND A.mparent=0 ORDER BY A.murutan ASC, A.mparent ASC";
            $sql = $this->db->query($query);        
            $jumlah = $sql->num_rows();
            if($jumlah>0){
          ?>          
            <div class="dropdown-divider" style="opacity: .3"></div>             
            <li class="nav-header pt-0">Administrator</li>                           
          <?php
            }
            foreach ($sql->result() as $row) {  
              if(empty($row->MLINK)){                      
          ?>
              <li class='nav-item has-treeview'>           
                <a href="#" class="nav-link text-sm">
                  <i class="nav-icon <?= $row->MICON; ?>"></i>
                  <p>
                    <?= $row->MCAPTION1; ?>
                    <i class="fas fa-angle-right right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">                
          <?php
              } else {
          ?>
              <li class='nav-item'>           
                <a href="<?= $row->MLINK; ?>" class="nav-link text-sm" role="<?= $row->MCAPTION1; ?>" onClick="_hideSidebarFocused();">
                  <i class="nav-icon <?= $row->MICON; ?>"></i>
                  <p>
                    <?= $row->MCAPTION1; ?>
                  </p>
                </a>
              </li>
          <?php                
              }
              $queryc = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
                        WHERE A.mparent=".$row->MID." AND A.mactive=1 ORDER BY A.murutan ASC";
              $sqlc = $this->db->query($queryc);
              $r = 0;
              foreach ($sqlc->result() as $row_c) {                                     
          ?>
              <li class="nav-item">
                  <a href="<?= $row_c->MLINK; ?>" class="nav-link text-sm" role="<?= $row_c->MCAPTION1; ?>" onClick="_hideSidebarFocused();">
                  <i class="fas fa-caret-right nav-icon"></i>                  
                  <p><?= $row_c->MCAPTION1; ?></p>
                </a>
              </li>              
          <?php
                $r++;
              }
              if(empty($row->MLINK)){          
                echo "</ul></li>";                           
              }
            }
          ?>

          <div class="dropdown-divider" style="opacity: .3"></div>             
          <li class="nav-header pt-0">Bantuan</li>                                             
          <li class="nav-item pb-2">
            <a href="#" class="nav-link text-sm">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>User Manual</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
</aside>
