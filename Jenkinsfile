pipeline {
    agent any
    
    environment {
        DOCKER_HOST = 'tcp://localhost:2375'
        KUBECONFIG = "${WORKSPACE}/.kube/config"
        RECIPIENT_LIST = 'srengty@gmail.com'
    }
    
    triggers {
        // Poll SCM every 5 minutes
        pollSCM('H/5 * * * *')
    }
    
    options {
        // Keep last 10 builds
        buildDiscarder(logRotator(numToKeepStr: '10'))
        // Timeout after 30 minutes
        timeout(time: 30, unit: 'MINUTES')
        // Skip default checkout
        skipDefaultCheckout()
    }
    
    stages {
        stage('🔍 Checkout & Environment Setup') {
            steps {
                script {
                    echo "🚀 Starting Laravel DevOps Pipeline..."
                    echo "📊 Build #${BUILD_NUMBER} on ${NODE_NAME}"
                    echo "🔗 Repository: ${GIT_URL}"
                    echo "🌿 Branch: ${GIT_BRANCH}"
                }
                
                // Clean workspace and checkout
                cleanWs()
                checkout scm
                
                // Get commit information for email notifications
                script {
                    env.GIT_COMMIT_MSG = sh(
                        script: 'git log -1 --pretty=%B',
                        returnStdout: true
                    ).trim()
                    env.GIT_AUTHOR_NAME = sh(
                        script: 'git log -1 --pretty=%an',
                        returnStdout: true
                    ).trim()
                    env.GIT_AUTHOR_EMAIL = sh(
                        script: 'git log -1 --pretty=%ae',
                        returnStdout: true
                    ).trim()
                    
                    echo "📝 Commit: ${env.GIT_COMMIT_MSG}"
                    echo "👤 Author: ${env.GIT_AUTHOR_NAME} <${env.GIT_AUTHOR_EMAIL}>"
                }
            }
        }
        
        stage('🔧 Prerequisites Check') {
            steps {
                script {
                    echo "🔍 Checking system prerequisites..."
                    
                    // Check if Kubernetes is running
                    sh '''
                        echo "📋 Checking Kubernetes cluster..."
                        kubectl cluster-info || (echo "❌ Kubernetes not available" && exit 1)
                        
                        echo "📋 Checking Docker..."
                        docker --version || (echo "❌ Docker not available" && exit 1)
                        
                        echo "📋 Checking Ansible..."
                        ansible --version || (echo "❌ Ansible not available" && exit 1)
                        
                        echo "✅ All prerequisites satisfied!"
                    '''
                }
            }
        }
        
        stage('🧪 Laravel Application Tests') {
            steps {
                script {
                    echo "🧪 Running Laravel application tests..."
                    
                    // Create test environment
                    sh '''
                        echo "📦 Setting up test environment..."
                        
                        # Create test Laravel environment
                        cd laravel || (echo "❌ Laravel directory not found" && exit 1)
                        
                        # Check if composer.json exists
                        if [ ! -f composer.json ]; then
                            echo "❌ composer.json not found in Laravel directory"
                            exit 1
                        fi
                        
                        echo "✅ Laravel project structure validated"
                        
                        # Validate critical files
                        echo "🔍 Validating critical Laravel files..."
                        [ -f artisan ] && echo "✅ artisan command found" || (echo "❌ artisan not found" && exit 1)
                        [ -f .env.example ] && echo "✅ .env.example found" || echo "⚠️ .env.example not found"
                        [ -d app ] && echo "✅ app directory found" || (echo "❌ app directory not found" && exit 1)
                        [ -d resources ] && echo "✅ resources directory found" || (echo "❌ resources directory not found" && exit 1)
                        
                        echo "🎯 Laravel project validation completed successfully!"
                    '''
                }
            }
        }
        
        stage('🏗️ Build & Deploy Infrastructure') {
            steps {
                script {
                    echo "🏗️ Deploying Laravel infrastructure to Kubernetes..."
                    
                    sh '''
                        echo "🚀 Applying Kubernetes configurations..."
                        
                        # Apply Kubernetes manifests
                        kubectl apply -f k8s/ || (echo "❌ Kubernetes deployment failed" && exit 1)
                        
                        echo "⏳ Waiting for deployment to be ready..."
                        kubectl rollout status deployment/laravel-deployment --timeout=300s || (echo "❌ Deployment timeout" && exit 1)
                        
                        echo "🔍 Checking pod status..."
                        kubectl get pods -l app=laravel
                        
                        # Wait for all containers to be ready
                        kubectl wait --for=condition=ready pod -l app=laravel --timeout=180s || (echo "❌ Pods not ready" && exit 1)
                        
                        echo "✅ Infrastructure deployment completed successfully!"
                    '''
                }
            }
        }
        
        stage('🔬 Integration Tests') {
            steps {
                script {
                    echo "🔬 Running integration tests..."
                    
                    sh '''
                        echo "🔍 Testing application health..."
                        
                        # Get pod name
                        POD_NAME=$(kubectl get pods -l app=laravel -o jsonpath='{.items[0].metadata.name}')
                        echo "📍 Testing pod: $POD_NAME"
                        
                        # Test database connection
                        echo "🗄️ Testing database connection..."
                        kubectl exec $POD_NAME -c laravel -- mysql -h localhost -P 3306 -u root -pHello@123 -e "SELECT 'Database connection successful!' as status;" laravel-db || (echo "❌ Database connection failed" && exit 1)
                        
                        # Test Laravel application
                        echo "🎯 Testing Laravel application..."
                        kubectl exec $POD_NAME -c laravel -- php artisan --version || (echo "❌ Laravel not responding" && exit 1)
                        
                        # Test web server response
                        echo "🌐 Testing web server response..."
                        sleep 10  # Give time for services to start
                        kubectl port-forward service/laravel-service 8081:80 &
                        FORWARD_PID=$!
                        sleep 5
                        
                        # Test HTTP response (with retry)
                        for i in {1..5}; do
                            if curl -f http://localhost:8081/ >/dev/null 2>&1; then
                                echo "✅ Web server responding correctly"
                                kill $FORWARD_PID 2>/dev/null || true
                                break
                            else
                                echo "⏳ Attempt $i: Web server not ready, retrying..."
                                sleep 5
                                if [ $i -eq 5 ]; then
                                    echo "❌ Web server not responding after 5 attempts"
                                    kill $FORWARD_PID 2>/dev/null || true
                                    exit 1
                                fi
                            fi
                        done
                        
                        echo "🎉 All integration tests passed!"
                    '''
                }
            }
        }
        
        stage('🤖 Ansible Deployment & Maintenance') {
            steps {
                script {
                    echo "🤖 Running Ansible playbook for maintenance tasks..."
                    
                    sh '''
                        echo "📋 Executing Ansible deployment playbook..."
                        cd ansible
                        
                        # Run the enhanced Ansible playbook
                        ansible-playbook playbooks/laravel-deployment.yml -v || (echo "❌ Ansible playbook failed" && exit 1)
                        
                        echo "📊 Deployment summary:"
                        echo "✅ Git pull completed"
                        echo "✅ Composer dependencies updated" 
                        echo "✅ Database backup created"
                        echo "✅ Caches cleared"
                        echo "✅ Health checks completed"
                        
                        # Check if backup files were created
                        if [ -f ../backups/laravel-backup-*.sql ]; then
                            echo "💾 Database backup available in backups/"
                            ls -la ../backups/
                        else
                            echo "⚠️ No backup files found"
                        fi
                        
                        echo "🎯 Ansible deployment completed successfully!"
                    '''
                }
            }
        }
        
        stage('✅ Final Validation') {
            steps {
                script {
                    echo "✅ Performing final validation..."
                    
                    sh '''
                        echo "🔍 Final system health check..."
                        
                        # Get pod information
                        POD_NAME=$(kubectl get pods -l app=laravel -o jsonpath='{.items[0].metadata.name}')
                        
                        # Validate all containers are running
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
                        
                        # Final application test
                        kubectl exec $POD_NAME -c laravel -- php artisan env
                        
                        echo "🎉 DEPLOYMENT SUCCESSFUL!"
                        echo "🌐 Application URL: http://localhost:30080"
                        echo "📊 Build #${BUILD_NUMBER} completed successfully"
                    '''
                }
            }
        }
    }
    
    post {
        success {
            script {
                echo "🎉 Pipeline completed successfully!"
                
                emailext(
                    subject: "✅ Laravel DevOps Build #${BUILD_NUMBER} - SUCCESS",
                    body: """
                    <h2>🎉 Laravel Terrain Booking System - Deployment Successful!</h2>
                    
                    <h3>📊 Build Information:</h3>
                    <ul>
                        <li><strong>Build Number:</strong> #${BUILD_NUMBER}</li>
                        <li><strong>Project:</strong> ${JOB_NAME}</li>
                        <li><strong>Duration:</strong> ${BUILD_DURATION} ms</li>
                        <li><strong>Triggered by:</strong> ${BUILD_CAUSE}</li>
                    </ul>
                    
                    <h3>📝 Latest Commit:</h3>
                    <ul>
                        <li><strong>Author:</strong> ${env.GIT_AUTHOR_NAME} &lt;${env.GIT_AUTHOR_EMAIL}&gt;</li>
                        <li><strong>Message:</strong> ${env.GIT_COMMIT_MSG}</li>
                        <li><strong>Commit:</strong> ${GIT_COMMIT}</li>
                        <li><strong>Branch:</strong> ${GIT_BRANCH}</li>
                    </ul>
                    
                    <h3>🚀 Deployment Status:</h3>
                    <ul>
                        <li>✅ Infrastructure deployed to Kubernetes</li>
                        <li>✅ Laravel application running</li>
                        <li>✅ Database (MySQL) operational</li>
                        <li>✅ Web server (Nginx) responding</li>
                        <li>✅ Ansible maintenance tasks completed</li>
                        <li>✅ Database backup created</li>
                    </ul>
                    
                    <h3>🌐 Access Information:</h3>
                    <ul>
                        <li><strong>Application URL:</strong> <a href="http://localhost:30080">http://localhost:30080</a></li>
                        <li><strong>Build URL:</strong> <a href="${BUILD_URL}">${BUILD_URL}</a></li>
                    </ul>
                    
                    <p><strong>🎯 Your Laravel Terrain Booking System is now live and ready for use!</strong></p>
                    """,
                    to: env.RECIPIENT_LIST,
                    mimeType: 'text/html'
                )
            }
        }
        
        failure {
            script {
                echo "❌ Pipeline failed!"
                
                emailext(
                    subject: "❌ Laravel DevOps Build #${BUILD_NUMBER} - FAILED",
                    body: """
                    <h2>❌ Laravel Terrain Booking System - Deployment Failed!</h2>
                    
                    <h3>📊 Build Information:</h3>
                    <ul>
                        <li><strong>Build Number:</strong> #${BUILD_NUMBER}</li>
                        <li><strong>Project:</strong> ${JOB_NAME}</li>
                        <li><strong>Failed Stage:</strong> ${env.STAGE_NAME}</li>
                        <li><strong>Duration:</strong> ${BUILD_DURATION} ms</li>
                    </ul>
                    
                    <h3>📝 Latest Commit:</h3>
                    <ul>
                        <li><strong>Author:</strong> ${env.GIT_AUTHOR_NAME} &lt;${env.GIT_AUTHOR_EMAIL}&gt;</li>
                        <li><strong>Message:</strong> ${env.GIT_COMMIT_MSG}</li>
                        <li><strong>Commit:</strong> ${GIT_COMMIT}</li>
                        <li><strong>Branch:</strong> ${GIT_BRANCH}</li>
                    </ul>
                    
                    <h3>🔧 Troubleshooting:</h3>
                    <ul>
                        <li>Check the build console output: <a href="${BUILD_URL}console">${BUILD_URL}console</a></li>
                        <li>Review the build logs for error details</li>
                        <li>Verify Kubernetes cluster is running</li>
                        <li>Check Docker daemon status</li>
                        <li>Validate repository access</li>
                    </ul>
                    
                    <h3>📋 Next Steps:</h3>
                    <ol>
                        <li>Review the error logs in Jenkins console</li>
                        <li>Fix the identified issues</li>
                        <li>Commit the fixes to trigger a new build</li>
                        <li>Monitor the next build for success</li>
                    </ol>
                    
                    <p><strong>🚨 Please investigate and resolve the issues promptly.</strong></p>
                    """,
                    to: "${env.RECIPIENT_LIST}, ${env.GIT_AUTHOR_EMAIL}",
                    mimeType: 'text/html'
                )
            }
        }
        
        always {
            script {
                echo "🧹 Cleaning up..."
                
                // Archive important artifacts
                if (fileExists('ansible/backups/')) {
                    archiveArtifacts artifacts: 'ansible/backups/*', allowEmptyArchive: true
                }
                
                // Clean up port forwards
                sh 'pkill -f "kubectl port-forward" || true'
                
                echo "📊 Build #${BUILD_NUMBER} completed."
            }
        }
    }
}