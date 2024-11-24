import re
import joblib
import pandas as pd
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer
from catboost import CatBoostClassifier
from nltk.tokenize import word_tokenize
from sklearn.multioutput import MultiOutputClassifier
from sklearn.model_selection import RandomizedSearchCV
from sklearn.feature_extraction.text import TfidfVectorizer
import sys

import nltk
nltk.download('punkt')
# Проверяем наличие сохраненной модели

try:
    multi_target_clf = joblib.load('./ml/multi_target_clf.pkl')
    vectorizer = joblib.load('./ml/vectorizer.pkl')
    print("Модель и векторизатор успешно загружены.")
    sys.exit()
except FileNotFoundError:
    print("Модель не найдена. Запуск обучения модели.")
    import model  # Запускаем модель, если её нет


# Загрузка данных
train = pd.read_excel('./data/train.xlsx')
test = pd.read_excel('./data/test.xlsx')


# Предобработка текста
def preprocess_text(text):
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
train['text'] = train['text'].apply(preprocess_text)
test['text'] = test['text'].apply(preprocess_text)


# Объединение текста и заголовка
train['combined'] = train['title'] + ' ' + train['text']
test['combined'] = test['title'] + ' ' + test['text']


# Создание признаков и целевых переменных
X_train = train['combined']
y_train = train[['category', 'class']]
X_test = test['combined']


# Векторизация текста
vectorizer = TfidfVectorizer(max_features=2000, ngram_range=(1, 2))  # Использование биграмм
X_train_tfidf = vectorizer.fit_transform(X_train)
X_test_tfidf = vectorizer.transform(X_test)


# Параметры для модели
params = {
    'learning_rate': [0.05, 0.1, 0.15],
    'l2_leaf_reg': [1, 3, 5, 10],
    'iterations': [100, 200, 300],
    'depth': [6, 8, 10],
    'border_count': [32, 64, 128]
}


# Модель CatBoost
catboost_model = CatBoostClassifier(cat_features=[], verbose=0)


# Поиск гиперпараметров с помощью RandomizedSearchCV
random_search = RandomizedSearchCV(estimator=catboost_model, param_distributions=params, n_iter=5, cv=3, verbose=1, random_state=42, n_jobs=-1)


# Обучение модели
multi_target_clf = MultiOutputClassifier(random_search, n_jobs=-1)
multi_target_clf.fit(X_train_tfidf, y_train)


# Сохранение модели и векторизатора
joblib.dump(multi_target_clf, 'multi_target_clf.pkl')
joblib.dump(vectorizer, 'vectorizer.pkl')

print("Модель и векторизатор успешно обучены и сохранены.")
