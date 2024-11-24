import os
import time
from search import main as search_main
from parcing import parse_gov_ru, process_documents_to_dataframe
from ml import preprocess_text, preprocess_text
import subprocess



BASE_URL = "http://government.ru/sanctions_measures/"
SAVED_LINKS_FILE = "./html/government_ru/links.txt"
OUTPUT_DIR = "./html/government_ru"
OUTPUT_CSV = "./data/gov_ru_draft.csv"


def check_links_file_exists():
    """Проверяет наличие файла links.txt"""
    return os.path.exists(SAVED_LINKS_FILE)


def start_parsing_and_processing():
    """Запускает парсинг и обработку данных"""
    print("Данные не найдены. Запуск стандартного парсинга...")
    parse_gov_ru(BASE_URL, OUTPUT_DIR)
    process_documents_to_dataframe(OUTPUT_DIR, OUTPUT_CSV)
    print("Парсинг и обработка завершены. Данные сохранены.")


def run_prediction():
    """Запуск предсказательной модели"""
    print("Запуск предсказательной модели...")
    subprocess.run(["python", "pred.py"])
    print("Работа классификатора завершена.")


def main():
    while True:
        if check_links_file_exists():
            print("Данные найдены. Выполняется проверка новых ссылок...")
            search_main(BASE_URL, SAVED_LINKS_FILE, OUTPUT_DIR, OUTPUT_CSV)
        else:
            start_parsing_and_processing()

        run_prediction()

        print("Ожидание: 24 часа до следующей проверки...")
        time.sleep(86400)


if __name__ == "__main__":
    main()
