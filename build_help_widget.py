# -*- coding: utf-8 -*-
"""
Gerador do Widget de Ajuda Oficial do i-Educar - Correção Definitiva de Renderização
- Corrige o bug de encolhimento flexbox (.ih-body-scroll agora usa display: block com min-height de 54px nos cards)
- Design idêntico à imagem de referência:
  - Cabeçalho #0d2238 com badge '?' e 'Passo a passo com imagens'
  - Barra de busca com placeholder 'O que você quer fazer? Ex.: enturmar, matricular'
  - Chips com contadores ('Todos 5', 'Matrícula 1', 'Enturmação 2', 'Ano letivo 1', etc.)
  - Subtítulo '5 guias em todos os temas'
  - 5 Cards principais destacados inicialmente + botão expansor para todos os 70 guias
  - Tag verde 'Usuários' com contorno verde (#10b981) e chevron (⌵ / ⌃)
  - Accordion que abre exibindo o passo a passo com as imagens OFICIAIS logo abaixo de cada etapa!
"""

import json
import re

def build_data():
    with open("ieducar_help_data.json", "r", encoding="utf-8") as f:
        guides = json.load(f)
    return guides

def generate_widget():
    guides = build_data()
    json_str = json.dumps(guides, ensure_ascii=False)

    js_code = f'''/*
 * WIDGET DE AJUDA FLUTUANTE I-EDUCAR (DESIGN EXATO DA REFERÊNCIA - CORRIGIDO)
 * Layout em Bloco Imune ao Encolhimento Flexbox | Accordion Funcional com Telas Embutidas
 */

(function() {{
  "use strict";

  if (document.getElementById("ieducar-help-root")) return;

  const IEDUCAR_HELP_DATA = {json_str};

  const widgetStyles = `
    /* Reset & Tipografia */
    #ieducar-help-root {{
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      font-size: 13px;
      line-height: 1.5;
      color: #1e293b;
      box-sizing: border-box;
      -webkit-font-smoothing: antialiased;
    }}
    #ieducar-help-root * {{
      box-sizing: border-box;
    }}

    /* Botão Flutuante (Formato Exato da Captura de Referência) */
    #ieducar-help-btn {{
      position: fixed;
      bottom: 52px;
      right: 24px;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      height: 36px;
      background: #47728f; /* Tom exato da captura oficial (#47728f) */
      color: #ffffff;
      border: none;
      /* Formato assimétrico idêntico à captura: lado esquerdo arredondado, topo direito quase reto e canto inferior direito suave */
      border-radius: 20px 3px 14px 20px;
      padding: 0 16px 0 11px;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.2px;
      cursor: pointer;
      box-shadow: 0 3px 10px rgba(71, 114, 143, 0.35);
      z-index: 999999;
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      outline: none;
      user-select: none;
    }}
    #ieducar-help-btn:hover {{
      background: #395c74;
      transform: translateY(-1px);
      box-shadow: 0 5px 14px rgba(71, 114, 143, 0.45);
    }}
    #ieducar-help-btn.active {{
      background: #2e4b5f;
      box-shadow: 0 2px 6px rgba(46, 75, 95, 0.5);
    }}
    #ieducar-help-btn svg {{
      width: 17px;
      height: 17px;
      flex-shrink: 0;
    }}
    #ieducar-help-btn span {{
      line-height: 1;
      font-weight: 700;
    }}

    /* Balão Flutuante Posicionado Acima do Botão */
    #ieducar-help-balloon {{
      position: fixed;
      bottom: 98px;
      right: 24px;
      width: 420px;
      max-width: calc(100vw - 32px);
      height: 590px;
      max-height: calc(100vh - 118px);
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 16px 40px rgba(71, 114, 143, 0.22), 0 4px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid #c9ddec;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      z-index: 999999;
      transform-origin: bottom right;
      opacity: 0;
      visibility: hidden;
      transform: scale(0.96) translateY(8px);
      pointer-events: none;
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.18s ease, visibility 0.18s ease;
    }}
    #ieducar-help-balloon.open {{
      opacity: 1;
      visibility: visible;
      transform: scale(1) translateY(0);
      pointer-events: auto;
    }}

    /* Cabeçalho Limpo: Menu de ajuda */
    .ih-balloon-header {{
      background: #47728f;
      color: #ffffff;
      padding: 13px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }}
    .ih-balloon-header h3 {{
      margin: 0;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: -0.2px;
      color: #ffffff;
    }}
    .ih-balloon-close {{
      background: transparent;
      border: none;
      color: #e9f0f8;
      width: 28px;
      height: 28px;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      transition: all 0.2s;
    }}
    .ih-balloon-close:hover {{
      color: #ffffff;
      background: rgba(255, 255, 255, 0.2);
    }}

    /* Seção de Busca e Chips */
    .ih-search-section {{
      padding: 12px 14px 8px;
      background: #ffffff;
      border-bottom: 1px solid #e9f0f8;
      flex-shrink: 0;
    }}
    .ih-input-wrap {{
      position: relative !important;
      display: flex !important;
      align-items: center !important;
      width: 100% !important;
    }}
    .ih-search-icon {{
      position: absolute !important;
      left: 12px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      color: #47728f !important;
      pointer-events: none !important;
      z-index: 5 !important;
      width: 16px !important;
      height: 16px !important;
      display: block !important;
    }}
    .ih-input {{
      width: 100% !important;
      height: 38px !important;
      padding: 0 32px 0 38px !important;
      padding-left: 38px !important;
      padding-right: 32px !important;
      border: 1px solid #c9ddec !important;
      border-radius: 8px !important;
      font-size: 13px !important;
      background: #ffffff !important;
      color: #1e293b !important;
      outline: none !important;
      box-sizing: border-box !important;
      text-indent: 0 !important;
      transition: border-color 0.15s, box-shadow 0.15s !important;
    }}
    .ih-input::placeholder {{
      color: #829ab1 !important;
      opacity: 1 !important;
      font-size: 12.5px !important;
    }}
    .ih-input:focus {{
      border-color: #47728f !important;
      box-shadow: 0 0 0 2px rgba(71, 114, 143, 0.2) !important;
    }}
    .ih-clear-btn {{
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      color: #94a3b8;
      font-size: 16px;
      cursor: pointer;
      display: none;
      padding: 2px;
    }}
    .ih-clear-btn.visible {{
      display: block;
    }}

    /* Chips de Filtro */
    .ih-chips-row {{
      display: flex;
      gap: 6px;
      overflow-x: auto;
      padding: 10px 0 4px;
      scrollbar-width: none;
    }}
    .ih-chips-row::-webkit-scrollbar {{
      display: none;
    }}
    .ih-chip {{
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 11.5px;
      font-weight: 500;
      border: 1px solid #d0deec;
      background: #ffffff;
      color: #334e68;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }}
    .ih-chip:hover {{
      background: #e9f0f8;
      border-color: #47728f;
      color: #47728f;
    }}
    .ih-chip.active {{
      background: #47728f;
      color: #ffffff;
      border-color: #47728f;
      font-weight: 700;
    }}
    .ih-chip .ih-count {{
      font-size: 10px;
      background: #e9f0f8;
      color: #47728f;
      border-radius: 9999px;
      padding: 1px 5px;
      font-weight: 700;
    }}
    .ih-chip.active .ih-count {{
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff;
    }}

    /* Resumo abaixo dos chips */
    .ih-summary-text {{
      font-size: 11.5px;
      color: #5c7891;
      margin-top: 6px;
      padding-left: 2px;
    }}

    /* Lista de Guias em Accordion (Layout em Bloco com Rolagem Natural) */
    .ih-body-scroll {{
      flex: 1 1 auto !important;
      min-height: 0 !important;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      padding: 12px 14px !important;
      background: #f4f8fc !important;
      display: block !important;
      scrollbar-width: thin;
      scrollbar-color: #b8cde2 transparent;
    }}
    .ih-body-scroll::-webkit-scrollbar {{
      width: 6px;
    }}
    .ih-body-scroll::-webkit-scrollbar-track {{
      background: transparent;
    }}
    .ih-body-scroll::-webkit-scrollbar-thumb {{
      background: #b8cde2;
      border-radius: 9999px;
    }}
    .ih-body-scroll::-webkit-scrollbar-thumb:hover {{
      background: #8faec9;
    }}

    /* Card de Accordion Totalmente Visível e Robusto */
    .ih-accordion-card {{
      display: block !important;
      width: 100% !important;
      min-height: 56px !important;
      background: #ffffff !important;
      border: 1px solid #dce7f3 !important;
      border-radius: 10px !important;
      margin-bottom: 8px !important;
      overflow: hidden !important;
      box-shadow: 0 1px 3px rgba(71, 114, 143, 0.06) !important;
      transition: border-color 0.2s, box-shadow 0.2s !important;
      box-sizing: border-box !important;
      flex-shrink: 0 !important;
    }}
    .ih-accordion-card:last-child {{
      margin-bottom: 4px !important;
    }}
    .ih-accordion-card:hover {{
      border-color: #47728f !important;
      box-shadow: 0 3px 8px rgba(71, 114, 143, 0.12) !important;
    }}
    .ih-accordion-card.open {{
      border-color: #47728f !important;
      box-shadow: 0 4px 14px rgba(71, 114, 143, 0.18) !important;
    }}

    /* Cabeçalho do Card Clicável */
    .ih-card-header {{
      padding: 12px 14px !important;
      cursor: pointer !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      user-select: none !important;
      min-height: 54px !important;
      box-sizing: border-box !important;
      gap: 8px !important;
    }}
    .ih-card-left {{
      display: flex !important;
      flex-direction: column !important;
      gap: 4px !important;
      padding-right: 8px !important;
      flex: 1 1 auto !important;
      min-width: 0 !important;
    }}
    .ih-card-title {{
      margin: 0 !important;
      font-size: 13.5px !important;
      font-weight: 700 !important;
      color: #173650 !important;
      line-height: 1.35 !important;
      word-break: break-word !important;
    }}
    .ih-card-tags {{
      display: flex !important;
      align-items: center !important;
      gap: 6px !important;
      flex-wrap: wrap !important;
    }}
    .ih-tag-user {{
      display: inline-block !important;
      border: 1px solid #47728f !important;
      color: #274b63 !important;
      background: #e9f0f8 !important;
      font-size: 10.5px !important;
      font-weight: 700 !important;
      padding: 1px 6px !important;
      border-radius: 4px !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
    }}
    .ih-tag-admin {{
      display: inline-block !important;
      border: 1px solid #2563eb !important;
      color: #1d4ed8 !important;
      background: #eff6ff !important;
      font-size: 10.5px !important;
      font-weight: 700 !important;
      padding: 1px 6px !important;
      border-radius: 4px !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
    }}
    .ih-subtopic-text {{
      font-size: 11.5px !important;
      color: #627d96 !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 220px !important;
    }}
    .ih-chevron {{
      color: #7994ab !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
      flex-shrink: 0 !important;
      width: 22px !important;
      height: 22px !important;
    }}
    .ih-accordion-card.open .ih-chevron {{
      transform: rotate(180deg) !important;
      color: #47728f !important;
    }}

    /* Conteúdo Retrátil do Accordion */
    .ih-card-body {{
      display: none;
      padding: 0 14px 14px !important;
      border-top: 1px solid #e9f0f8;
      background: #ffffff;
    }}
    .ih-accordion-card.open .ih-card-body {{
      display: block !important;
    }}

    .ih-path-bar {{
      background: #e9f0f8 !important;
      border: 1px solid #d2e2f0;
      border-radius: 6px;
      padding: 7px 10px;
      font-size: 11.5px;
      font-weight: 600;
      color: #20415a !important;
      margin: 10px 0 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }}

    /* Passos com Imagens Embutidas Logo Abaixo */
    .ih-walkthrough-list {{
      display: flex;
      flex-direction: column;
      gap: 12px;
    }}
    .ih-step-row {{
      display: flex;
      align-items: flex-start;
      gap: 9px;
      background: #f8fafc;
      border: 1px solid #dce7f3;
      border-radius: 6px;
      padding: 10px;
      transition: background 0.15s, border-color 0.15s;
    }}
    .ih-step-row:hover {{
      background: #e9f0f8;
      border-color: #c9ddec;
    }}
    .ih-step-num {{
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      border-radius: 5px;
      background: #47728f;
      color: #ffffff;
      font-size: 11.5px;
      font-weight: 800;
      flex-shrink: 0;
      margin-top: 1px;
    }}
    .ih-step-content {{
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }}
    .ih-step-text {{
      font-size: 12.5px;
      color: #27435b;
      line-height: 1.45;
    }}

    /* Imagem Oficial Logo Abaixo do Passo */
    .ih-step-img-box {{
      border: 1px solid #c9ddec;
      border-radius: 6px;
      overflow: hidden;
      background: #ffffff;
      box-shadow: 0 1px 4px rgba(71, 114, 143, 0.08);
    }}
    .ih-img {{
      width: 100%;
      height: auto;
      max-height: 190px;
      object-fit: cover;
      display: block;
      cursor: zoom-in;
      transition: opacity 0.2s;
    }}
    .ih-img:hover {{
      opacity: 0.95;
    }}
    .ih-caption {{
      padding: 6px 9px;
      background: #e9f0f8;
      border-top: 1px solid #dce7f3;
      font-size: 10.5px;
      color: #355874;
      line-height: 1.35;
      font-style: italic;
    }}

    .ih-highlight {{
      background-color: #dbe8f6;
      color: #173852;
      padding: 0 3px;
      border-radius: 3px;
      font-weight: 700;
    }}

    /* Botão de Expansão para Todos os Guias */
    .ih-show-all-btn {{
      display: block;
      width: 100%;
      padding: 10px 14px;
      margin: 8px 0 16px;
      text-align: center;
      background: #f1f5f9;
      border: 1px dashed #cbd5e1;
      border-radius: 8px;
      color: #1e3a5c;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }}
    .ih-show-all-btn:hover {{
      background: #e2e8f0;
      border-color: #0d2238;
      color: #0d2238;
    }}

    /* Modal Lightbox */
    #ih-zoom-modal {{
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.88);
      backdrop-filter: blur(4px);
      z-index: 1000000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      cursor: zoom-out;
    }}
    #ih-zoom-modal.open {{
      display: flex;
    }}
    #ih-zoom-img {{
      max-width: 95vw;
      max-height: 90vh;
      border-radius: 6px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      cursor: default;
    }}
    #ih-zoom-close {{
      position: absolute;
      top: 16px;
      right: 20px;
      color: #ffffff;
      font-size: 24px;
      background: rgba(255, 255, 255, 0.15);
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }}

    .ih-empty {{
      text-align: center;
      padding: 30px 14px;
      color: #64748b;
    }}
  `;

  const styleEl = document.createElement("style");
  styleEl.type = "text/css";
  styleEl.id = "ieducar-help-styles";
  styleEl.innerHTML = widgetStyles;
  document.head.appendChild(styleEl);

  const root = document.createElement("div");
  root.id = "ieducar-help-root";
  root.innerHTML = `
    <!-- Botão Flutuante (Fiel à captura oficial) -->
    <button id="ieducar-help-btn" type="button" aria-label="Ajuda do i-Educar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
        <line x1="12" y1="17" x2="12.01" y2="17"></line>
      </svg>
      <span>Ajuda</span>
    </button>

    <!-- Balão Flutuante Acima do Botão -->
    <div id="ieducar-help-balloon" role="dialog" aria-modal="true" aria-labelledby="ih-balloon-title">
      <!-- Cabeçalho -->
      <div class="ih-balloon-header">
        <h3 id="ih-balloon-title">Menu de ajuda</h3>
        <button id="ih-balloon-close" class="ih-balloon-close" title="Fechar (Esc)">&times;</button>
      </div>

      <!-- Barra de Busca e Chips -->
      <div class="ih-search-section">
        <div class="ih-input-wrap">
          <svg class="ih-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input
            type="text"
            id="ih-input"
            class="ih-input"
            placeholder="O que você quer fazer? Ex.: enturmar, matricular"
            autocomplete="off"
          />
          <button id="ih-clear-btn" class="ih-clear-btn" title="Limpar">&times;</button>
        </div>

        <div class="ih-chips-row">
          <button type="button" class="ih-chip active" data-filter="all">Todos <span class="ih-count" id="cnt-all">70</span></button>
          <button type="button" class="ih-chip" data-filter="matricula">Matrícula <span class="ih-count" id="cnt-mat">25</span></button>
          <button type="button" class="ih-chip" data-filter="enturma">Enturmação <span class="ih-count" id="cnt-ent">7</span></button>
          <button type="button" class="ih-chip" data-filter="ano letivo">Ano letivo <span class="ih-count" id="cnt-ano">5</span></button>
          <button type="button" class="ih-chip" data-filter="nota">Notas e faltas <span class="ih-count" id="cnt-not">22</span></button>
          <button type="button" class="ih-chip" data-filter="censo">Censo <span class="ih-count" id="cnt-cen">6</span></button>
        </div>

        <div id="ih-summary-text" class="ih-summary-text">70 guias em todos os temas</div>
      </div>

      <!-- Lista de Guias em Accordion -->
      <div id="ih-body-scroll" class="ih-body-scroll"></div>
    </div>

    <!-- Lightbox Zoom -->
    <div id="ih-zoom-modal">
      <button id="ih-zoom-close" title="Fechar">&times;</button>
      <img id="ih-zoom-img" src="" alt="Tela Ampliada" />
    </div>
  `;
  document.body.appendChild(root);

  const btn = document.getElementById("ieducar-help-btn");
  const balloon = document.getElementById("ieducar-help-balloon");
  const closeBtn = document.getElementById("ih-balloon-close");
  const input = document.getElementById("ih-input");
  const clearBtn = document.getElementById("ih-clear-btn");
  const bodyScroll = document.getElementById("ih-body-scroll");
  const summaryText = document.getElementById("ih-summary-text");
  const chips = document.querySelectorAll(".ih-chip");
  const zoomModal = document.getElementById("ih-zoom-modal");
  const zoomImg = document.getElementById("ih-zoom-img");
  const zoomClose = document.getElementById("ih-zoom-close");

  let activeFilter = "all";

  function normalize(str) {{
    return (str || "")
      .normalize("NFD")
      .replace(/[\\u0300-\\u036f]/g, "")
      .toLowerCase()
      .trim();
  }}

  // Contadores dinâmicos dos chips
  function updateChipCounts() {{
    const allCount = IEDUCAR_HELP_DATA.length;
    const matCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("matric")).length;
    const entCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("enturm")).length;
    const anoCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("ano letiv")).length;
    const notCount = IEDUCAR_HELP_DATA.filter(d => {{
      const s = normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" "));
      return s.includes("nota") || s.includes("falta") || s.includes("diario");
    }}).length;
    const cenCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("censo")).length;

    const elAll = document.getElementById("cnt-all");
    const elMat = document.getElementById("cnt-mat");
    const elEnt = document.getElementById("cnt-ent");
    const elAno = document.getElementById("cnt-ano");
    const elNot = document.getElementById("cnt-not");
    const elCen = document.getElementById("cnt-cen");

    if (elAll) elAll.innerText = allCount;
    if (elMat) elMat.innerText = matCount;
    if (elEnt) elEnt.innerText = entCount;
    if (elAno) elAno.innerText = anoCount;
    if (elNot) elNot.innerText = notCount;
    if (elCen) elCen.innerText = cenCount;
  }}

  const ROOT_SYNONYMS = [
    {{ test: /(entrum|etrum|enturm)/, root: "enturm", terms: ["enturmacao", "enturmar", "turma"] }},
    {{ test: /(matric|matrik|matriu)/, root: "matric", terms: ["matricula", "matricular", "aluno"] }},
    {{ test: /(profes|docent|servid)/, root: "profess", terms: ["professor", "alocacao", "docente"] }},
    {{ test: /(bloq|trava|prazo)/, root: "bloquei", terms: ["bloqueio", "travar", "data limite"] }},
    {{ test: /(nota|falta|boletim|diario|arredon)/, root: "nota", terms: ["notas", "faltas", "avaliacao"] }},
    {{ test: /(cens|inep|educacen)/, root: "censo", terms: ["censo escolar", "educacenso", "importar"] }},
    {{ test: /(transf|saida|aband)/, root: "transfer", terms: ["transferencia", "abandono", "saida"] }},
    {{ test: /(ano letiv|etap|bimestr|trimestr)/, root: "ano letiv", terms: ["ano letivo", "modulo", "etapas"] }}
  ];

  function extractSemanticRoots(rawStr) {{
    const norm = normalize(rawStr);
    const roots = [];
    ROOT_SYNONYMS.forEach(s => {{
      if (s.test.test(norm)) {{
        roots.push(s.root);
        roots.push(...s.terms);
      }}
    }});
    return [...new Set(roots)];
  }}

  function highlightText(text, tokens) {{
    if (!tokens || tokens.length === 0 || !text) return text;
    let res = text;
    tokens.forEach(tok => {{
      if (tok.length >= 3) {{
        const regex = new RegExp("(" + tok + ")", "gi");
        res = res.replace(regex, "<mark class='ih-highlight'>$1</mark>");
      }}
    }});
    return res;
  }}

  function toggleBalloon() {{
    const isOpen = balloon.classList.contains("open");
    if (isOpen) {{
      balloon.classList.remove("open");
      btn.classList.remove("active");
    }} else {{
      balloon.classList.add("open");
      btn.classList.add("active");
      setTimeout(() => input.focus(), 150);
    }}
  }}

  btn.addEventListener("click", toggleBalloon);
  closeBtn.addEventListener("click", toggleBalloon);

  window.addEventListener("keydown", (e) => {{
    if (e.key === "Escape") {{
      if (zoomModal.classList.contains("open")) zoomModal.classList.remove("open");
      else if (balloon.classList.contains("open")) toggleBalloon();
    }}
  }});

  clearBtn.addEventListener("click", () => {{
    input.value = "";
    clearBtn.classList.remove("visible");
    render();
    input.focus();
  }});

  chips.forEach((c) => {{
    c.addEventListener("click", () => {{
      chips.forEach((ch) => ch.classList.remove("active"));
      c.classList.add("active");
      activeFilter = c.getAttribute("data-filter");
      render();
    }});
  }});

  input.addEventListener("input", () => {{
    if (input.value.trim().length > 0) clearBtn.classList.add("visible");
    else clearBtn.classList.remove("visible");
    render();
  }});

  window.__ihZoom = function(src) {{
    zoomImg.src = src;
    zoomModal.classList.add("open");
  }};
  zoomModal.addEventListener("click", (e) => {{
    if (e.target !== zoomImg) zoomModal.classList.remove("open");
  }});
  zoomClose.addEventListener("click", () => zoomModal.classList.remove("open"));

  function resolveImg(imgObj) {{
    if (!imgObj) return {{ localSrc: "", remoteSrc: "" }};
    const filename = imgObj.local_filename || (imgObj.url ? imgObj.url.split("/").pop() : "");
    const localSrc = "/help_images/" + filename;
    const remoteSrc = imgObj.url;
    return {{ localSrc, remoteSrc }};
  }}

  // Alternar Accordion (Abre/Fecha)
  window.__ihToggleCard = function(headerEl) {{
    const card = headerEl.closest(".ih-accordion-card");
    if (!card) return;
    const isOpen = card.classList.contains("open");
    card.classList.toggle("open", !isOpen);
  }};

  // Pontuação de Relevância
  function scoreItem(item, queryNorm, roots, rawTokens) {{
    let score = 0;
    const titleNorm = normalize(item.title);
    const subtopicNorm = normalize(item.subtopic);
    const pathNorm = normalize(item.path);
    const keywordsNorm = (item.keywords || []).map(normalize);
    const stepsNorm = (item.steps || []).map(s => normalize(s.text)).join(" ");

    roots.forEach(r => {{
      if (titleNorm.includes(r)) score += 350;
      if (subtopicNorm.includes(r)) score += 200;
      if (keywordsNorm.some(k => k.includes(r))) score += 180;
      if (pathNorm.includes(r)) score += 100;
      if (stepsNorm.includes(r)) score += 60;
    }});

    if (queryNorm.length >= 3) {{
      if (titleNorm.includes(queryNorm)) score += 400;
      if (subtopicNorm.includes(queryNorm)) score += 250;
      if (pathNorm.includes(queryNorm)) score += 150;
    }}

    rawTokens.forEach(t => {{
      if (t.length < 2) return;
      if (titleNorm.includes(t)) score += 120;
      if (subtopicNorm.includes(t)) score += 80;
      if (keywordsNorm.some(k => k.includes(t))) score += 60;
      if (stepsNorm.includes(t)) score += 30;
    }});

    return score;
  }}

  function render() {{
    const raw = input.value.trim();
    const q = normalize(raw);
    const roots = extractSemanticRoots(raw);
    const tokens = q.split(/\\s+/).filter(Boolean);

    let scoredList = [];

    IEDUCAR_HELP_DATA.forEach((item) => {{
      if (activeFilter !== "all") {{
        const nTitle = normalize(item.title);
        const nSub = normalize(item.subtopic);
        const nKeys = (item.keywords || []).map(normalize);
        const allText = nTitle + " " + nSub + " " + nKeys.join(" ");
        const matchChip = (activeFilter === "nota")
          ? (allText.includes("nota") || allText.includes("falta") || allText.includes("diario"))
          : allText.includes(activeFilter);
        if (!matchChip) return;
      }}

      if (!q) {{
        scoredList.push({{ item, score: 1 }});
        return;
      }}

      const score = scoreItem(item, q, roots, tokens);
      if (score > 0) {{
        scoredList.push({{ item, score }});
      }}
    }});

    if (q) {{
      scoredList.sort((a, b) => b.score - a.score);
    }}

    // Atualizar contadores dos chips
    updateChipCounts();

    // Atualizar texto de resumo
    if (!q) {{
      if (activeFilter === "all") {{
        summaryText.innerText = "70 guias em todos os temas";
      }} else {{
        summaryText.innerText = `${{scoredList.length}} guias nesta categoria`;
      }}
    }} else {{
      summaryText.innerText = `${{scoredList.length}} ${{scoredList.length === 1 ? "guia encontrado" : "guias encontrados"}} para "${{raw}}"`;
    }}

    if (scoredList.length === 0) {{
      bodyScroll.innerHTML = `
        <div class="ih-empty">
          <div style="font-size: 26px; margin-bottom: 8px;">🔍</div>
          <h4 style="margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #1e293b;">Nenhum guia encontrado</h4>
          <p style="margin: 0; font-size: 12px; color: #64748b;">Tente buscar por termos como <em>matrícula</em>, <em>enturmar</em>, <em>ano letivo</em> ou <em>notas</em>.</p>
        </div>
      `;
      return;
    }}

    const cardsHtml = scoredList.map((entry, index) => {{
      const doc = entry.item;
      // Se houver busca ativa, o melhor resultado abre automaticamente
      const isOpen = Boolean(q && index === 0);
      const isUser = doc.category === "Usuários";
      const tagCls = isUser ? "ih-tag-user" : "ih-tag-admin";

      const highlightedTitle = highlightText(doc.title, tokens);

      const stepsList = doc.steps || [];
      const stepsHtml = (stepsList.length > 0)
        ? `
          <div class="ih-walkthrough-list">
            ${{stepsList.map((st, i) => {{
              const hasImg = st.image && (st.image.local_filename || st.image.url);
              const imgPaths = hasImg ? resolveImg(st.image) : null;

              return `
                <div class="ih-step-row">
                  <span class="ih-step-num">${{i + 1}}</span>
                  <div class="ih-step-content">
                    <div class="ih-step-text">${{highlightText(st.text, tokens)}}</div>
                    ${{hasImg ? `
                      <div class="ih-step-img-box">
                        <img
                          class="ih-img"
                          src="${{imgPaths.localSrc}}"
                          data-fallback="${{imgPaths.remoteSrc}}"
                          alt="${{st.image.alt || doc.title}}"
                          referrerpolicy="no-referrer"
                          loading="lazy"
                          onclick="event.stopPropagation(); window.__ihZoom(this.src);"
                          onerror="if (this.src !== this.dataset.fallback && this.dataset.fallback) {{ this.src = this.dataset.fallback; }} else {{ this.style.display='none'; }}"
                        />
                        ${{st.image.alt ? `<div class="ih-caption">📸 ${{st.image.alt}}</div>` : ""}}
                      </div>
                    ` : ""}}
                  </div>
                </div>
              `;
            }}).join("")}}
          </div>
        `
        : "";

      return `
        <div class="ih-accordion-card ${{isOpen ? "open" : ""}}">
          <div class="ih-card-header" onclick="window.__ihToggleCard(this)">
            <div class="ih-card-left">
              <h4 class="ih-card-title">${{highlightedTitle}}</h4>
              <div class="ih-card-tags">
                <span class="${{tagCls}}">${{doc.category}}</span>
                <span class="ih-subtopic-text">${{doc.subtopic}}</span>
              </div>
            </div>
            <div class="ih-chevron">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>

          <div class="ih-card-body">
            ${{doc.path ? `
              <div class="ih-path-bar">
                <span>📍</span>
                <span><strong>Onde Clicar:</strong> ${{doc.path}}</span>
              </div>
            ` : ""}}

            ${{stepsHtml}}
          </div>
        </div>
      `;
    }}).join("");

    bodyScroll.innerHTML = cardsHtml;
  }}

  render();
}})();
'''

    with open("ieducar-help-widget.js", "w", encoding="utf-8") as f:
        f.write(js_code)
    print("Gerado com sucesso: ieducar-help-widget.js (Correção de Renderização)")

if __name__ == "__main__":
    generate_widget()
