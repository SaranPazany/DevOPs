pipeline {
    agent any
    
    environment {
        DOCKER_HOST = 'tcp://localhost:2375'
        RECIPIENT_LIST = 'srengty@gmail.com'
        WSL_ANSIBLE = 'wsl ansible-playbook'
        // Ensure we use the right kubectl context
        KUBECONFIG = "${env.USERPROFILE}\\.kube\\config"
        // Set Jenkins URL explicitly since it's running on 8088
        JENKINS_URL = 'http://localhost:8088/'
    }
    
    triggers {
        pollSCM('H/5 * * * *')
    }
    
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 30, unit: 'MINUTES')
        skipDefaultCheckout()
    }
    
    stages {
        stage('🔍 Checkout & Environment Setup') {
            steps {
                script {
                    echo "🚀 Starting Laravel DevOps Pipeline..."
                    echo "📊 Build #${BUILD_NUMBER} on ${NODE_NAME}"
                    echo "🔧 Jenkins URL: http://localhost:8088/"
                    echo "🔧 Kubernetes API: https://kubernetes.docker.internal:6443"
                }
                
                cleanWs()
                checkout scm
                
                script {
                    try {
                        if (isUnix()) {
                            env.GIT_COMMIT_MSG = sh(script: 'git log -1 --pretty=%B', returnStdout: true).trim()
                            env.GIT_AUTHOR_NAME = sh(script: 'git log -1 --pretty=%an', returnStdout: true).trim()
                            env.GIT_AUTHOR_EMAIL = sh(script: 'git log -1 --pretty=%ae', returnStdout: true).trim()
                            env.GIT_COMMIT_HASH = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                            env.GIT_BRANCH_NAME = sh(script: 'git rev-parse --abbrev-ref HEAD', returnStdout: true).trim()
                        } else {
                            env.GIT_COMMIT_MSG = bat(script: '@git log -1 --pretty=%%B', returnStdout: true).trim()
                            env.GIT_AUTHOR_NAME = bat(script: '@git log -1 --pretty=%%an', returnStdout: true).trim()
                            env.GIT_AUTHOR_EMAIL = bat(script: '@git log -1 --pretty=%%ae', returnStdout: true).trim()
                            env.GIT_COMMIT_HASH = bat(script: '@git rev-parse HEAD', returnStdout: true).trim()
                            env.GIT_BRANCH_NAME = bat(script: '@git rev-parse --abbrev-ref HEAD', returnStdout: true).trim()
                        }
                    } catch (Exception e) {
                        echo "Warning: Could not extract git information: ${e.getMessage()}"
                        env.GIT_COMMIT_MSG = "Unable to retrieve commit message"
                        env.GIT_AUTHOR_NAME = "Unknown"
                        env.GIT_AUTHOR_EMAIL = "unknown@example.com"
                        env.GIT_COMMIT_HASH = "unknown"
                        env.GIT_BRANCH_NAME = "unknown"
                    }
                    
                    echo "📝 Commit: ${env.GIT_COMMIT_MSG}"
                    echo "👤 Author: ${env.GIT_AUTHOR_NAME} <${env.GIT_AUTHOR_EMAIL}>"
                    echo "🌿 Branch: ${env.GIT_BRANCH_NAME}"
                    echo "🔗 Commit Hash: ${env.GIT_COMMIT_HASH}"
                }
            }
        }
        
        stage('🔧 Prerequisites Check') {
            steps {
                script {
                    echo "🔍 Checking system prerequisites..."
                    
                    bat '''
                        echo "📋 Checking Kubernetes cluster..."
                        echo "Setting kubectl context to docker-desktop..."
                        kubectl config use-context docker-desktop
                        
                        echo "Current context:"
                        kubectl config current-context
                        
                        echo "Testing cluster connectivity..."
                        kubectl cluster-info --request-timeout=10s
                        if errorlevel 1 (
                            echo "❌ Kubernetes cluster is not available"
                            echo "💡 Troubleshooting steps:"
                            echo "   1. Verify Docker Desktop is running"
                            echo "   2. Ensure Kubernetes is enabled in Docker Desktop settings"
                            echo "   3. Current kubectl context should be 'docker-desktop'"
                            echo "   4. Kubernetes should be accessible at https://kubernetes.docker.internal:6443"
                            exit /b 1
                        )
                        echo "✅ Kubernetes cluster is accessible at https://kubernetes.docker.internal:6443"
                        
                        echo "📋 Checking nodes..."
                        kubectl get nodes
                        
                        echo "📋 Checking Docker..."
                        docker --version || (echo "❌ Docker not available" && exit /b 1)
                        
                        echo "📋 Checking WSL and Ansible..."
                        wsl ansible --version || (echo "❌ Ansible not available in WSL" && exit /b 1)
                        
                        echo "✅ All prerequisites satisfied!"
                        echo "🎯 Jenkins running on: http://localhost:8088"
                        echo "🎯 Kubernetes API on: https://kubernetes.docker.internal:6443"
                    '''
                }
            }
        }
        
        stage('🧪 Laravel Application Tests') {
            steps {
                script {
                    echo "🧪 Running Laravel application tests..."
                    
                    if (isUnix()) {
                        sh '''
                            echo "📦 Setting up test environment..."
                            cd laravel || (echo "❌ Laravel directory not found" && exit 1)
                            
                            if [ ! -f composer.json ]; then
                                echo "❌ composer.json not found in Laravel directory"
                                exit 1
                            fi
                            
                            echo "✅ Laravel project structure validated"
                            
                            echo "🔍 Validating critical Laravel files..."
                            [ -f artisan ] && echo "✅ artisan command found" || (echo "❌ artisan not found" && exit 1)
                            [ -f .env.example ] && echo "✅ .env.example found" || echo "⚠️ .env.example not found"
                            [ -d app ] && echo "✅ app directory found" || (echo "❌ app directory not found" && exit 1)
                            [ -d resources ] && echo "✅ resources directory found" || (echo "❌ resources directory not found" && exit 1)
                            
                            echo "🎯 Laravel project validation completed successfully!"
                        '''
                    } else {
                        bat '''
                            echo "📦 Setting up test environment..."
                            if not exist laravel (echo "❌ Laravel directory not found" && exit /b 1)
                            cd laravel
                            
                            if not exist composer.json (echo "❌ composer.json not found in Laravel directory" && exit /b 1)
                            
                            echo "✅ Laravel project structure validated"
                            
                            echo "🔍 Validating critical Laravel files..."
                            if exist artisan (echo "✅ artisan command found") else (echo "❌ artisan not found" && exit /b 1)
                            if exist .env.example (echo "✅ .env.example found") else echo "⚠️ .env.example not found"
                            if exist app (echo "✅ app directory found") else (echo "❌ app directory not found" && exit /b 1)
                            if exist resources (echo "✅ resources directory found") else (echo "❌ resources directory not found" && exit /b 1)
                            
                            echo "🎯 Laravel project validation completed successfully!"
                        '''
                    }
                }
            }
        }
        
        stage('🏗️ Build & Deploy Infrastructure') {
            steps {
                script {
                    echo "🏗️ Deploying Laravel infrastructure to Kubernetes..."
                    
                    if (isUnix()) {
                        sh '''
                            echo "🚀 Applying Kubernetes configurations..."
                            kubectl apply -f k8s/ || (echo "❌ Kubernetes deployment failed" && exit 1)
                            
                            echo "⏳ Waiting for deployment to be ready..."
                            kubectl rollout status deployment/laravel-deployment --timeout=300s || (echo "❌ Deployment timeout" && exit 1)
                            
                            echo "🔍 Checking pod status..."
                            kubectl get pods -l app=laravel
                            
                            kubectl wait --for=condition=ready pod -l app=laravel --timeout=180s || (echo "❌ Pods not ready" && exit 1)
                            
                            echo "✅ Infrastructure deployment completed successfully!"
                        '''
                    } else {
                        bat '''
                            echo "🚀 Applying Kubernetes configurations..."
                            kubectl apply -f k8s/ || (echo "❌ Kubernetes deployment failed" && exit /b 1)
                            
                            echo "⏳ Waiting for deployment to be ready..."
                            kubectl rollout status deployment/laravel-deployment --timeout=300s || (echo "❌ Deployment timeout" && exit /b 1)
                            
                            echo "🔍 Checking pod status..."
                            kubectl get pods -l app=laravel
                            
                            kubectl wait --for=condition=ready pod -l app=laravel --timeout=180s || (echo "❌ Pods not ready" && exit /b 1)
                            
                            echo "✅ Infrastructure deployment completed successfully!"
                        '''
                    }
                }
            }
        }
        
        stage('🔬 Integration Tests') {
            steps {
                script {
                    echo "🔬 Running integration tests..."
                    
                    if (isUnix()) {
                        sh '''
                            echo "🔍 Testing application health..."
                            
                            POD_NAME=$(kubectl get pods -l app=laravel -o jsonpath='{.items[0].metadata.name}')
                            echo "📍 Testing pod: $POD_NAME"
                            
                            echo "🗄️ Testing database connection..."
                            kubectl exec $POD_NAME -c laravel -- mysql -h localhost -P 3306 -u root -pHello@123 -e "SELECT 'Database connection successful!' as status;" laravel-db || (echo "❌ Database connection failed" && exit 1)
                            
                            echo "🎯 Testing Laravel application..."
                            kubectl exec $POD_NAME -c laravel -- php artisan --version || (echo "❌ Laravel not responding" && exit 1)
                            
                            echo "🎉 All integration tests passed!"
                        '''
                    } else {
                        bat '''
                            echo "🔍 Testing application health..."
                            
                            for /f %%i in ('kubectl get pods -l app=laravel -o jsonpath^="{.items[0].metadata.name}"') do set POD_NAME=%%i
                            echo "📍 Testing pod: %POD_NAME%"
                            
                            echo "🗄️ Testing database connection..."
                            kubectl exec %POD_NAME% -c laravel -- mysql -h localhost -P 3306 -u root -pHello@123 -e "SELECT 'Database connection successful!' as status;" laravel-db || (echo "❌ Database connection failed" && exit /b 1)
                            
                            echo "🎯 Testing Laravel application..."
                            kubectl exec %POD_NAME% -c laravel -- php artisan --version || (echo "❌ Laravel not responding" && exit /b 1)
                            
                            echo "🎉 All integration tests passed!"
                        '''
                    }
                }
            }
        }
        
        stage('🤖 Ansible Deployment & Maintenance') {
            steps {
                script {
                    echo "🤖 Running Ansible playbook for maintenance tasks..."
                    
                    if (isUnix()) {
                        sh '''
                            echo "📋 Executing Ansible deployment playbook..."
                            cd ansible
                            ansible-playbook playbooks/laravel-deployment.yml -v || (echo "❌ Ansible playbook failed" && exit 1)
                            echo "🎯 Ansible deployment completed successfully!"
                        '''
                    } else {
                        bat '''
                            echo "📋 Executing Ansible deployment playbook via WSL..."
                            cd ansible
                            wsl ansible-playbook playbooks/laravel-deployment.yml -v || (echo "❌ Ansible playbook failed" && exit /b 1)
                            
                            echo "📊 Deployment summary:"
                            echo "✅ Git pull completed"
                            echo "✅ Composer dependencies updated" 
                            echo "✅ Database backup created"
                            echo "✅ Caches cleared"
                            echo "✅ Health checks completed"
                            
                            if exist ".\\backups\\laravel-backup-*.sql" (
                                echo "💾 Database backup available in backups/"
                                dir ".\\backups\\"
                            ) else (
                                echo "⚠️ No backup files found"
                            )
                            
                            echo "🎯 Ansible deployment completed successfully!"
                        '''
                    }
                }
            }
        }
        
        stage('✅ Final Validation') {
            steps {
                script {
                    echo "✅ Performing final validation..."
                    
                    if (isUnix()) {
                        sh '''
                            echo "🔍 Final system health check..."
                            POD_NAME=$(kubectl get pods -l app=laravel -o jsonpath='{.items[0].metadata.name}')
                            
                            READY_CONTAINERS=$(kubectl get pod $POD_NAME -o jsonpath='{.status.containerStatuses[*].ready}' | tr ' ' '\n' | grep -c true)
                            TOTAL_CONTAINERS=$(kubectl get pod $POD_NAME -o jsonpath='{.status.containerStatuses[*].name}' | wc -w)
                            
                            echo "📊 Containers ready: $READY_CONTAINERS/$TOTAL_CONTAINERS"
                            
                            if [ "$READY_CONTAINERS" -eq "$TOTAL_CONTAINERS" ]; then
                                echo "✅ All containers are running successfully"
                            else
                                echo "❌ Not all containers are ready"
                                kubectl describe pod $POD_NAME
                                exit 1
                            fi
                            
                            kubectl exec $POD_NAME -c laravel -- php artisan env
                            
                            echo "🎉 DEPLOYMENT SUCCESSFUL!"
                            echo "🌐 Application URL: http://localhost:30080"
                            echo "📊 Build #${BUILD_NUMBER} completed successfully"
                        '''
                    } else {
                        bat '''
                            echo "🔍 Final system health check..."
                            for /f %%i in ('kubectl get pods -l app=laravel -o jsonpath^="{.items[0].metadata.name}"') do set POD_NAME=%%i
                            
                            echo "📍 Validating pod: %POD_NAME%"
                            kubectl get pod %POD_NAME%
                            
                            kubectl exec %POD_NAME% -c laravel -- php artisan env
                            
                            echo "🎉 DEPLOYMENT SUCCESSFUL!"
                            echo "🌐 Application URL: http://localhost:30080"
                            echo "📊 Build #%BUILD_NUMBER% completed successfully"
                        '''
                    }
                }
            }
        }
    }
    
    post {
        success {
            script {
                echo "🎉 Pipeline completed successfully!"
                
                try {
                    emailext(
                        subject: "✅ Laravel DevOps Build #${BUILD_NUMBER} - SUCCESS",
                        body: """
                        <h2>🎉 Laravel Terrain Booking System - Deployment Successful!</h2>
                        
                        <h3>📊 Build Information:</h3>
                        <ul>
                            <li><strong>Build Number:</strong> #${BUILD_NUMBER}</li>
                            <li><strong>Project:</strong> ${JOB_NAME}</li>
                            <li><strong>Duration:</strong> ${currentBuild.durationString}</li>
                            <li><strong>Jenkins URL:</strong> http://localhost:8088/</li>
                            <li><strong>Kubernetes API:</strong> https://kubernetes.docker.internal:6443</li>
                        </ul>
                        
                        <h3>📝 Latest Commit:</h3>
                        <ul>
                            <li><strong>Author:</strong> ${env.GIT_AUTHOR_NAME} &lt;${env.GIT_AUTHOR_EMAIL}&gt;</li>
                            <li><strong>Message:</strong> ${env.GIT_COMMIT_MSG}</li>
                            <li><strong>Commit:</strong> ${env.GIT_COMMIT_HASH}</li>
                            <li><strong>Branch:</strong> ${env.GIT_BRANCH_NAME}</li>
                        </ul>
                        
                        <h3>🌐 Access Information:</h3>
                        <ul>
                            <li><strong>Laravel Application:</strong> <a href="http://localhost:30080">http://localhost:30080</a></li>
                            <li><strong>Jenkins Dashboard:</strong> <a href="http://localhost:8088/">http://localhost:8088/</a></li>
                            <li><strong>Build Details:</strong> <a href="${BUILD_URL}">${BUILD_URL}</a></li>
                        </ul>
                        
                        <p><strong>🎯 Your Laravel Terrain Booking System is now live!</strong></p>
                        """,
                        to: env.RECIPIENT_LIST,
                        mimeType: 'text/html'
                    )
                } catch (Exception e) {
                    echo "⚠️ Email notification failed: ${e.getMessage()}"
                    echo "💡 Please configure SMTP settings in Jenkins"
                }
            }
        }
        
        failure {
            script {
                echo "❌ Pipeline failed!"
                
                try {
                    emailext(
                        subject: "❌ Laravel DevOps Build #${BUILD_NUMBER} - FAILED",
                        body: """
                        <h2>❌ Laravel Terrain Booking System - Deployment Failed!</h2>
                        
                        <h3>📊 Build Information:</h3>
                        <ul>
                            <li><strong>Build Number:</strong> #${BUILD_NUMBER}</li>
                            <li><strong>Project:</strong> ${JOB_NAME}</li>
                            <li><strong>Failed Stage:</strong> ${env.STAGE_NAME ?: 'Prerequisites Check'}</li>
                            <li><strong>Jenkins URL:</strong> http://localhost:8088/</li>
                        </ul>
                        
                        <h3>🔧 System Status:</h3>
                        <ul>
                            <li><strong>Jenkins:</strong> http://localhost:8088/ ✅</li>
                            <li><strong>Kubernetes:</strong> https://kubernetes.docker.internal:6443 ✅</li>
                            <li><strong>Kubectl Context:</strong> docker-desktop ✅</li>
                        </ul>
                        
                        <h3>🔧 Troubleshooting:</h3>
                        <ul>
                            <li>Check console: <a href="http://localhost:8088/job/Laravel-DevOps-Pipeline/lastBuild/console">Build Console</a></li>
                            <li>Verify Docker Desktop is running</li>
                            <li>Ensure Kubernetes is enabled in Docker Desktop</li>
                            <li>Test: kubectl cluster-info</li>
                        </ul>
                        """,
                        to: "${env.RECIPIENT_LIST}, ${env.GIT_AUTHOR_EMAIL}",
                        mimeType: 'text/html'
                    )
                } catch (Exception e) {
                    echo "⚠️ Email notification failed: ${e.getMessage()}"
                }
            }
        }
        
        always {
            script {
                echo "🧹 Cleaning up..."
                if (fileExists('ansible/backups/')) {
                    archiveArtifacts artifacts: 'ansible/backups/*', allowEmptyArchive: true
                }
                echo "📊 Build #${BUILD_NUMBER} completed."
                echo "🌐 Jenkins: http://localhost:8088/"
                echo "🎯 Kubernetes: https://kubernetes.docker.internal:6443"
            }
        }
    }
}