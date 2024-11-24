import joblib
import pandas as pd
import numpy as np
import sys
from sklearn.feature_extraction.text import TfidfVectorizer


# Проверяем наличие сохраненной модели
import joblib
import sys

# Проверяем наличие сохраненной модели
try:
    multi_target_clf = joblib.load('/app/ml/multi_target_clf.pkl')  # Указываем путь внутри контейнера
    vectorizer = joblib.load('/app/ml/vectorizer.pkl')  # Указываем путь внутри контейнера
    print("Модель и векторизатор успешно загружены.")
except FileNotFoundError:
    print("Модель не найдена. Запуск обучения модели.")
    import model  # Запускаем модель, если её нет


# Загрузка данных
train = pd.read_excel('./data/train.xlsx')
test = pd.read_excel('./data/test.xlsx')


# Применение предобработки текста
def preprocess_text(text):
    import re
    from nltk.tokenize import word_tokenize
    from nltk.corpus import stopwords
    from nltk.stem import WordNetLemmatizer

    if isinstance(text, str):
        text = re.sub(r'\d+', '', text)  # Удаление чисел
        text = re.sub(r'[^\w\s]', '', text)  # Удаление пунктуации
        text = text.lower()  # Приведение текста к нижнему регистру
        tokens = word_tokenize(text)
        lemmatizer = WordNetLemmatizer()
        tokens = [lemmatizer.lemmatize(token) for token in tokens if token not in stopwords.words('russian')]
        return ' '.join(tokens)
    else:
        return ''


# Применение предобработки
test['text'] = test['text'].apply(preprocess_text)
test['combined'] = test['title'] + ' ' + test['text']


# Векторизация
X_test = test['combined']
X_test_tfidf = vectorizer.transform(X_test)


# Прогнозирование
y_pred = multi_target_clf.predict(X_test_tfidf)


# Преобразование в нужный формат
y_pred = np.array(y_pred)


# Убираем лишнее измерение, если оно присутствует
if len(y_pred.shape) == 3 and y_pred.shape[0] == 1:
    y_pred = y_pred[0]

# Убедимся, что форма соответствует ожиданиям
if y_pred.shape[0] == len(test) and y_pred.shape[1] == 2:  # Должно быть (23, 2)
    pred_category, pred_class = y_pred[:, 0], y_pred[:, 1]
else:
    raise ValueError(f"Ошибка: форма y_pred ({y_pred.shape}) не соответствует ожиданиям.")


# Добавление предсказаний в DataFrame
test['category'] = pred_category
test['class'] = pred_class
final_df = pd.concat([train, test], ignore_index=True)


# Сохранение
final_df.to_csv('./data/data_final.csv', index=False)
print("Результаты сохранены.")
