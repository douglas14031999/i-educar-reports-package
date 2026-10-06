# -*- coding: utf-8 -*-
"""
Script de Webscraping da Documentação Completa do i-Educar (Usuários e Administradores)
Extrai capítulos, seções, caminhos de menu, passos, tabelas, imagens oficiais e gera
uma base de dados indexada para o Widget de Ajuda Flutuante.
"""

import urllib.request
import json
import re
import unicodedata
from bs4 import BeautifulSoup

PAGES = [
    # Guia de Usuários (7 Capítulos)
    {
        "url": "https://ieducar.org/guia/usuarios/conhecendo-sistema",
        "category": "Usuários",
        "chapter_num": 1,
        "chapter_title": "Conhecendo o Sistema"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/modulo-enderecamento-pessoas",
        "category": "Usuários",
        "chapter_num": 2,
        "chapter_title": "Módulo Endereçamento e Pessoas"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/cadastros-sistema-educacional",
        "category": "Usuários",
        "chapter_num": 3,
        "chapter_title": "Cadastros do Sistema Educacional"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/servidores-e-professores",
        "category": "Usuários",
        "chapter_num": 4,
        "chapter_title": "Servidores e Professores"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/configuracoes",
        "category": "Usuários",
        "chapter_num": 5,
        "chapter_title": "Configurações do Sistema"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/ano-letivo-escolar-matriculas",
        "category": "Usuários",
        "chapter_num": 6,
        "chapter_title": "Ano Letivo Escolar e Matrículas"
    },
    {
        "url": "https://ieducar.org/guia/usuarios/regras-avaliacao-notas",
        "category": "Usuários",
        "chapter_num": 7,
        "chapter_title": "Regras de Avaliação e Notas"
    },
    # Guia de Administradores
    {
        "url": "https://ieducar.org/guia/administradores/setup-inicial",
        "category": "Administradores",
        "chapter_num": 8,
        "chapter_title": "Setup Inicial da Rede"
    },
    {
        "url": "https://ieducar.org/guia/administradores/importar-censo",
        "category": "Administradores",
        "chapter_num": 9,
        "chapter_title": "Importação do Censo Escolar"
    }
]

def remove_accents(input_str):
    if not input_str:
        return ""
    nfkd_form = unicodedata.normalize('NFKD', input_str)
    return "".join([c for c in nfkd_form if not unicodedata.combining(c)]).lower()

def clean_text(text):
    if not text:
        return ""
    text = re.sub(r'\s+', ' ', text).strip()
    return text

def extract_keywords(title, chapter, path, content_list):
    full_text = f"{title} {chapter} {path} " + " ".join(content_list[:4])
    words = re.findall(r'\b[a-zA-ZÀ-ÿ0-9_\-]{3,}\b', full_text.lower())
    
    # Common stop words to discard
    stop_words = {
        'para', 'com', 'por', 'uma', 'como', 'mais', 'este', 'esta', 'esse',
        'essa', 'isso', 'aquele', 'aquela', 'onde', 'quando', 'qual', 'quais',
        'cada', 'todos', 'todas', 'todo', 'toda', 'pelo', 'pela', 'pelos', 'pelas',
        'dos', 'das', 'seu', 'sua', 'seus', 'suas', 'ele', 'ela', 'eles', 'elas',
        'que', 'nos', 'nas', 'sistema', 'ieducar', 'educar', 'figura', 'ilustrado'
    }
    
    unique_words = set()
    for w in words:
        if w not in stop_words and len(w) > 2:
            unique_words.add(w)
            unique_words.add(remove_accents(w))
            
    # Add domain synonyms
    title_lower = title.lower()
    if 'matr' in title_lower:
        unique_words.update(['aluno', 'estudante', 'vaga', 'enturmar', 'turma', 'matricula', 'cadastro'])
    if 'enturm' in title_lower:
        unique_words.update(['lote', 'turma', 'copiar enturmacao', 'sala', 'alocar'])
    if 'ano letivo' in title_lower:
        unique_words.update(['calendario', 'etapas', 'bimestre', 'trimestre', 'abrir ano', 'fechar ano'])
    if 'nota' in title_lower or 'avalia' in title_lower:
        unique_words.update(['boletim', 'media', 'conceito', 'recuperacao', 'falta', 'frequencia', 'diario'])
    if 'bloqueio' in title_lower:
        unique_words.update(['travar', 'prazo', 'limite', 'fechar lancamento', 'data limite'])
    if 'censo' in title_lower:
        unique_words.update(['educacenso', 'inep', 'migracao', 'carga inicial', 'importar'])
    if 'servidor' in title_lower or 'professor' in title_lower:
        unique_words.update(['docente', 'alocacao', 'quadro', 'horario', 'disciplina', 'componente'])
    if 'transfer' in title_lower or 'abandono' in title_lower:
        unique_words.update(['saida', 'evasao', 'desfazer', 'atestado'])
        
    return sorted(list(unique_words))

def scrape_all():
    all_sections = []
    global_id = 1
    
    print(f"Iniciando raspagem de {len(PAGES)} guias...")
    
    for page_info in PAGES:
        url = page_info["url"]
        category = page_info["category"]
        chapter_title = page_info["chapter_title"]
        print(f"-> Acessando: {chapter_title} ({url})")
        
        try:
            req = urllib.request.Request(url, headers={
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
            })
            html_content = urllib.request.urlopen(req).read().decode("utf-8")
            soup = BeautifulSoup(html_content, "html.parser")
        except Exception as e:
            print(f"   ERRO ao carregar {url}: {e}")
            continue
            
        # Clean unwanted tags
        for t in soup.find_all(["nav", "footer", "header", "script", "style"]):
            t.decompose()
            
        main_content = soup.find("main") or soup.find("article") or soup.find("body")
        
        # We find all h2, h3 and iterate
        nodes = main_content.find_all(["h2", "h3", "p", "blockquote", "ul", "ol", "table"])
        
        current_section = None
        
        # If the page starts with content before any H2, capture it as intro
        first_h = main_content.find(["h2", "h3"])
        
        intro_section = {
            "id": f"guide-{global_id}",
            "category": category,
            "chapter": chapter_title,
            "title": f"Visão Geral: {chapter_title}",
            "path": "",
            "summary": "",
            "content": [],
            "steps": [],
            "tables": [],
            "images": [],
            "keywords": []
        }
        global_id += 1
        current_section = intro_section
        
        for node in nodes:
            tag_name = node.name
            
            if tag_name in ["h2", "h3"]:
                header_text = clean_text(node.get_text())
                if not header_text or header_text.lower() in [
                    "capítulos", "guias do i-educar", "o projeto", "comunidade", "implementar", "conteúdo"
                ]:
                    continue
                    
                # Save previous section if it has meaningful content
                if current_section and (current_section["content"] or current_section["images"] or current_section["steps"]):
                    # finalize previous
                    current_section["summary"] = current_section["content"][0] if current_section["content"] else ""
                    current_section["keywords"] = extract_keywords(
                        current_section["title"],
                        current_section["chapter"],
                        current_section["path"],
                        current_section["content"]
                    )
                    all_sections.append(current_section)
                    
                current_section = {
                    "id": f"guide-{global_id}",
                    "category": category,
                    "chapter": chapter_title,
                    "title": header_text,
                    "path": "",
                    "summary": "",
                    "content": [],
                    "steps": [],
                    "tables": [],
                    "images": [],
                    "keywords": []
                }
                global_id += 1
                
            elif current_section:
                # Check for location/menu path in blockquote or styled p
                node_text = clean_text(node.get_text())
                
                if tag_name == "blockquote" or "Localização:" in node_text or "Módulo" in node_text and ">" in node_text:
                    if ">" in node_text:
                        clean_path = re.sub(r'^(Localização\s*:\s*|Caminho\s*:\s*)', '', node_text, flags=re.I).strip()
                        current_section["path"] = clean_path
                        continue
                        
                # Extract ordered/unordered list as steps
                if tag_name in ["ol", "ul"]:
                    for li in node.find_all("li", recursive=False):
                        li_text = clean_text(li.get_text())
                        if li_text and len(li_text) > 3:
                            current_section["steps"].append(li_text)
                    continue
                    
                # Extract tables
                if tag_name == "table":
                    rows = []
                    for tr in node.find_all("tr"):
                        cells = [clean_text(td.get_text()) for td in tr.find_all(["th", "td"])]
                        if any(cells):
                            rows.append(cells)
                    if rows:
                        current_section["tables"].append(rows)
                    continue
                    
                # Check images
                imgs = node.find_all("img")
                for img in imgs:
                    src = img.get("src", "")
                    if src and not src.endswith(".svg") and "ieducar-hor" not in src:
                        full_url = src if src.startswith("http") else "https://ieducar.org" + src
                        alt = clean_text(img.get("alt", ""))
                        caption = clean_text(img.get("title", "")) or alt
                        
                        # Avoid duplicates
                        if not any(i["url"] == full_url for i in current_section["images"]):
                            current_section["images"].append({
                                "url": full_url,
                                "alt": alt or f"Tela ilustrativa: {current_section['title']}",
                                "caption": caption
                            })
                            
                # Paragraph content
                if tag_name == "p" and node_text:
                    if not any(node_text.startswith(x) for x in ["← Voltar", "Pular para", "Software público de gestão", "Mantido pela"]):
                        if node_text not in current_section["content"]:
                            current_section["content"].append(node_text)
                            
        # Final section of page
        if current_section and (current_section["content"] or current_section["images"] or current_section["steps"]):
            current_section["summary"] = current_section["content"][0] if current_section["content"] else ""
            current_section["keywords"] = extract_keywords(
                current_section["title"],
                current_section["chapter"],
                current_section["path"],
                current_section["content"]
            )
            all_sections.append(current_section)
            
    # Post-processing: infer paths and convert numbered sentences in content to steps if empty
    for s in all_sections:
        # If no explicit steps, extract from content sentences that look like steps
        if not s["steps"] and s["content"]:
            generated_steps = []
            for line in s["content"]:
                # Check for "Para...", "Clique em...", "Acesse...", "Selecione...", "Informe..."
                sentences = re.split(r'(?<=[.!?])\s+', line)
                for sentence in sentences:
                    sentence = sentence.strip()
                    if re.match(r'^(Para |Acesse |Clique |Selecione |Informe |Preencha |Defina |Ao pressionar |No caso |Quando )', sentence, re.I) and len(sentence) > 15:
                        generated_steps.append(sentence)
            if generated_steps:
                s["steps"] = generated_steps[:6]
                
        # If no path, try to guess or leave generic
        if not s["path"]:
            title_lower = s["title"].lower()
            if "matrícula" in title_lower or "matricula" in title_lower:
                s["path"] = "Módulo Escola > Cadastros > Aluno > Matrículas"
            elif "enturma" in title_lower:
                s["path"] = "Módulo Escola > Movimentações > Enturmações em lote"
            elif "ano letivo" in title_lower:
                s["path"] = "Módulo Escola > Cadastros > Escola > Editar ano letivo"
            elif "servidor" in title_lower or "professor" in title_lower:
                s["path"] = "Módulo Servidores > Cadastros > Servidor > Alocação"
            elif "avaliação" in title_lower or "nota" in title_lower:
                s["path"] = "Módulo Escola > Cadastros > Regras de Avaliação"
            elif "censo" in title_lower:
                s["path"] = "Módulo EducaCenso > Importação / Carga Inicial"
            elif "pessoa" in title_lower or "endereço" in title_lower:
                s["path"] = "Módulo Pessoas > Cadastros > Física / Jurídica"
            elif "configura" in title_lower or "permiss" in title_lower:
                s["path"] = "Módulo Configurações > Usuários > Permissões"
                
    print(f"\nRaspagem concluída com sucesso!")
    print(f"Total de tópicos estruturados: {len(all_sections)}")
    total_imgs = sum(len(s["images"]) for s in all_sections)
    print(f"Total de imagens oficiais mapeadas: {total_imgs}")
    
    # Save to JSON
    out_file = "ieducar_help_data.json"
    with open(out_file, "w", encoding="utf-8") as f:
        json.dump(all_sections, f, ensure_ascii=False, indent=2)
    print(f"Salvo com sucesso em: {out_file}")
    
    return all_sections

if __name__ == "__main__":
    scrape_all()
